--
-- PostgreSQL database dump
--

-- Dumped from database version 14.5
-- Dumped by pg_dump version 14.5

-- Started on 2023-04-23 20:13:29

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 247 (class 1255 OID 35151)
-- Name: change(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.change() RETURNS void
    LANGUAGE plpgsql
    AS $$
DECLARE
s record;
BEGIN

for s in (select * from saison order by annee desc) loop		
		update saison set annee = (s.annee + 1)
		where saison.id_club = s.id_club
		and saison.id_championnat = s.id_championnat
		and saison.annee = s.annee;
	end loop;


end;
$$;


ALTER FUNCTION public.change() OWNER TO postgres;

--
-- TOC entry 258 (class 1255 OID 26598)
-- Name: classement_saison(integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.classement_saison(anneeselect integer, ligueselect integer) RETURNS TABLE(id_championnat integer, id_club integer, nom_club character varying, nb_points integer, nb_buts_marques integer, nb_buts_encaisse integer, nb_match_joue integer, nb_match_gagne integer, nb_match_nul integer, nb_match_perdu integer, logo text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
		SELECT saison.id_championnat, club.id_club, club.nom_club, saison.nb_points, saison.nb_buts_marques, saison.nb_buts_encaisse, 
		 
		(select * from get_nb_match_joue(club.id_club, anneeSelect)) as nb_match_joue, 
		saison.nb_matchs_gagne as nb_match_gagne, 
		saison.nb_matchs_nul as nb_match_nul,
		saison.nb_matchs_perdu as nb_match_perdu,
		club.logo
       	FROM club
        INNER JOIN saison ON club.id_club = saison.id_club
        WHERE saison.annee = anneeSelect AND saison.id_championnat = ligueSelect
        ORDER BY saison.nb_points DESC;
END;
$$;


ALTER FUNCTION public.classement_saison(anneeselect integer, ligueselect integer) OWNER TO postgres;

--
-- TOC entry 248 (class 1255 OID 26599)
-- Name: getAnneeSaison(date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."getAnneeSaison"(datej date) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
	date_min date;
	date_max date;
BEGIN

SELECT MAX(date_match), MIN(date_match) INTO date_max, date_min FROM rencontre;

IF dateJ BETWEEN date_min AND date_max THEN
	return(EXTRACT(YEAR FROM date_min));
END IF;
return 0;
END;
$$;


ALTER FUNCTION public."getAnneeSaison"(datej date) OWNER TO postgres;

--
-- TOC entry 257 (class 1255 OID 26816)
-- Name: get_info_club(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_info_club(p_id_club integer) RETURNS TABLE(ville character varying, nom_club character varying, annee_fondation integer, president character varying, entraineur character varying, id_championnat integer, nb_buts_marques integer, nb_buts_encaisse integer, nb_matchs_gagne integer, nb_matchs_nul integer, nb_matchs_perdu integer, nb_points integer, logo text)
    LANGUAGE plpgsql
    AS $$
BEGIN
RETURN QUERY
	select c.ville, c.nom_club, c.annee_fondation, c.president, c.entraineur, s.id_championnat, 
	s.nb_buts_marques, s.nb_buts_encaisse, s.nb_matchs_gagne, s.nb_matchs_nul, s.nb_matchs_perdu, 
	s.nb_points, c.logo
	from club c
	inner join saison s ON s.id_club = c.id_club
	where c.id_club = p_id_club;
end;
$$;


ALTER FUNCTION public.get_info_club(p_id_club integer) OWNER TO postgres;

--
-- TOC entry 261 (class 1255 OID 26819)
-- Name: get_matchs_journee(integer, integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_matchs_journee(p_annee integer, p_ligue integer, p_journee integer) RETURNS TABLE(nom_club_domicile character varying, id_club_domicile integer, nom_club_visiteur character varying, id_club_visiteur integer, journee integer, score_domicile integer, score_visiteur integer, isjoue integer, date_match date, logo_club_domicile text, logo_club_visiteur text)
    LANGUAGE plpgsql
    AS $$
DECLARE 
	anneeMax integer =0;
	p_id_equipe_domicile integer =0;
	p_id_equipe_visituer integer =0;
	tablebackup text;
	res_Colonne integer;
BEGIN
	anneeMax = (select max(annee) from  saison);
	raise notice 'Saison en cours : %', anneeMax;

	if (p_annee = anneeMax)  then -- chercher dans la table rencontre
	    return query 
	        select C1.nom_club as nom_club_domicile, C1.id_club as id_club_domicile, 
			C2.nom_club as nom_club_visiteur, C2.id_club as id_club_visiteur, 
            R1.journee,R1.score_domicile,R1.score_visiteur, R1.isjoue, R1.date_match, 
			C1.logo, C2.logo
	        from rencontre R1
	        inner join Club C1 on R1.id_equipe_domicile = C1.id_club
	        inner join Club C2 on R1.id_equipe_visiteur = C2.id_club
			inner join Saison S1 ON S1.id_club = C2.id_club
			inner join Saison S2 ON S2.id_club = C2.id_club
	        where R1.id_equipe_domicile = C1.id_club
	        and R1.id_equipe_visiteur = C2.id_club
	        and R1.journee = p_journee
            and S1.id_championnat  = p_ligue
            and S2.id_championnat  = p_ligue
			and S1.annee = p_annee
            and S2.annee = p_annee
	        order by R1.journee;

	
	else  -- il faut chercher dans la table backup_rencontre_p_annee*
	tablebackup := 'rencontre_backup_' || p_annee::text;
	Execute E'SELECT count(*) FROM pg_class where pg_class.relname = \'' || tablebackup || E'\''  into res_Colonne;
	if(res_Colonne = 1) then 

	return query 
	Execute 'select C1.nom_club, C1.id_club, C2.nom_club, C2.id_club, 
	R1.journee,R1.score_domicile,
	R1.score_visiteur, R1.isjoue, R1.date_match, C1.logo, C2.logo
	        from '||tablebackup||'  R1
	        inner join Club C1 on R1.id_equipe_domicile = C1.id_club
	        inner join Club C2 on R1.id_equipe_visiteur = C2.id_club
			inner join Saison S1 ON S1.id_club = C2.id_club
			inner join Saison S2 ON S2.id_club = C2.id_club    
	        where R1.id_equipe_domicile = C1.id_club
	        and R1.id_equipe_visiteur = C2.id_club
	        and R1.journee = ' ||p_journee||'
			and S1.id_championnat  = ' ||p_ligue||'
			and S2.id_championnat  = ' ||p_ligue||'
			and S1.annee = ' ||p_annee||'
            and S2.annee = ' ||p_annee||'
	        order by R1.journee';
	else
		raise notice ' cette saison % nexiste pas',p_annee ;
		return;
	end if;
end if;
END ;
$$;


ALTER FUNCTION public.get_matchs_journee(p_annee integer, p_ligue integer, p_journee integer) OWNER TO postgres;

--
-- TOC entry 243 (class 1255 OID 26814)
-- Name: get_nb_buts_contre(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_nb_buts_contre(p_id_club integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$

DECLARE
nb_buts_contre integer = 0;

BEGIN
nb_buts_contre := nb_buts_contre + (select sum(score_visiteur)
									from rencontre
									where id_equipe_domicile = p_id_club);
								
nb_buts_contre := nb_buts_contre + (select sum(score_domicile)
									from rencontre
									where id_equipe_visiteur = p_id_club);

return nb_buts_contre;

END;
$$;


ALTER FUNCTION public.get_nb_buts_contre(p_id_club integer) OWNER TO postgres;

--
-- TOC entry 244 (class 1255 OID 26813)
-- Name: get_nb_buts_pour(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_nb_buts_pour(p_id_club integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$

DECLARE
nb_buts_pour integer = 0;

BEGIN
nb_buts_pour := nb_buts_pour + (select sum(score_domicile)
								from rencontre
								where id_equipe_domicile = p_id_club);
								
nb_buts_pour := nb_buts_pour + (select sum(score_visiteur)
								from rencontre
								where id_equipe_visiteur = p_id_club);

return nb_buts_pour;

END;
$$;


ALTER FUNCTION public.get_nb_buts_pour(p_id_club integer) OWNER TO postgres;

--
-- TOC entry 249 (class 1255 OID 26600)
-- Name: get_nb_match_gagne(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_nb_match_gagne(p_id_club integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
res_score_match record;
nb_match_gagne integer = 0;
BEGIN
for res_score_match in (select score_domicile, score_visiteur
						from rencontre
						where isjoue = 3
						and id_equipe_domicile = p_id_club) loop

	if res_score_match.score_domicile > res_score_match.score_visiteur then 
		nb_match_gagne = nb_match_gagne+1;
	end if;

end loop;

for res_score_match in (select score_domicile, score_visiteur
						from rencontre
						where isjoue = 3
						and id_equipe_visiteur = p_id_club) loop

	if res_score_match.score_domicile < res_score_match.score_visiteur then 
		nb_match_gagne = nb_match_gagne+1;
	end if;

end loop;

return nb_match_gagne;
END;
$$;


ALTER FUNCTION public.get_nb_match_gagne(p_id_club integer) OWNER TO postgres;

--
-- TOC entry 246 (class 1255 OID 26943)
-- Name: get_nb_match_joue(integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_nb_match_joue(p_id_club integer, p_annee integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$

DECLARE
nb_match_joue integer;
begin
/*
SELECT COUNT(r.isjoue)
FROM club c
left  JOIN rencontre r ON c.id_club = r.id_equipe_domicile
and r.isjoue = 3 or 
c.id_club = r.id_equipe_visiteur
and r.isjoue = 3
where id_club = p_id_club
GROUP BY c.id_club, c.nom_club, r.isjoue
--ORDER BY id_club ASC;
ORDER BY nb_match_joue ASC INTO nb_match_joue;

raise notice '%', nb_match_joue;
*/
select sum(nb_matchs_gagne + nb_matchs_nul + nb_matchs_perdu) 
from saison where annee = p_annee and id_club = p_id_club
INTO nb_match_joue;

return nb_match_joue;

end;

$$;


ALTER FUNCTION public.get_nb_match_joue(p_id_club integer, p_annee integer) OWNER TO postgres;

--
-- TOC entry 250 (class 1255 OID 26602)
-- Name: get_nb_match_nul(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_nb_match_nul(p_id_club integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
res_score_match record;
nb_match_nul integer = 0;
BEGIN
for res_score_match in (select score_domicile, score_visiteur
						from rencontre
						where isjoue = 3
						and id_equipe_domicile = p_id_club) loop

	if res_score_match.score_domicile = res_score_match.score_visiteur then 
		nb_match_nul = nb_match_nul+1;
	end if;

end loop;

for res_score_match in (select score_domicile, score_visiteur
						from rencontre
						where isjoue = 3
						and id_equipe_visiteur = p_id_club) loop

	if res_score_match.score_domicile = res_score_match.score_visiteur then 
		nb_match_nul = nb_match_nul+1;
	end if;

end loop;

return nb_match_nul;
END;
$$;


ALTER FUNCTION public.get_nb_match_nul(p_id_club integer) OWNER TO postgres;

--
-- TOC entry 251 (class 1255 OID 26603)
-- Name: get_nb_match_perdu(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_nb_match_perdu(p_id_club integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
res_score_match record;
nb_match_perdu integer = 0;
BEGIN
for res_score_match in (select score_domicile, score_visiteur
						from rencontre
						where isjoue = 3
						and id_equipe_domicile = p_id_club) loop

	if res_score_match.score_domicile < res_score_match.score_visiteur then 
		nb_match_perdu = nb_match_perdu+1;
	end if;

end loop;

for res_score_match in (select score_domicile, score_visiteur
						from rencontre
						where isjoue = 3
						and id_equipe_visiteur = p_id_club) loop

	if res_score_match.score_domicile > res_score_match.score_visiteur then 
		nb_match_perdu = nb_match_perdu+1;
	end if;

end loop;

return nb_match_perdu;
END;
$$;


ALTER FUNCTION public.get_nb_match_perdu(p_id_club integer) OWNER TO postgres;

--
-- TOC entry 252 (class 1255 OID 26605)
-- Name: init_rencontres(integer, date, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.init_rencontres(p_annee integer, p_date_debut date, p_id_championnat integer) RETURNS void
    LANGUAGE plpgsql
    AS $$

DECLARE

id_club_random int[];
id_last_club_temp integer;
nb_club integer;
index_inf integer;
index_sup integer;
i integer;
j integer;
id_arbitre_random int[];
date_match date;

BEGIN

--Obtient tout les identifiants des équipes
id_club_random := ARRAY(select id_club 
                        from saison
                        where annee = p_annee
						and id_championnat = p_id_championnat
                        order by random()
                       );

--raise notice '%', id_club_random;

j= 1;
WHILE j < 20 LOOP


--raise notice 'Jour : %', j;

index_sup = 20;
index_inf = 1;

-- Définition des rencontres
--raise notice 'Matchs Allés';
--raise notice '%', p_date_debut + 7*(j - 1);
date_match = p_date_debut + 7*(j - 1);


id_arbitre_random := ARRAY(Select id_arbitre
From arbitre
order by random()
limit (10));
--raise notice '%', id_arbitre_random;

WHILE index_inf < 11 LOOP
	--Insert les matchs aller pour une journée
    insert into rencontre values (
        id_club_random[index_inf],
        id_club_random[index_sup],
        id_arbitre_random[index_inf],
        date_match,
        0,
        0,
        1,
        j        
    );
    --raise notice '% : %', id_club_random[index_inf], id_club_random[index_sup];
    
    index_inf = index_inf + 1;
    index_sup = index_sup - 1;
END LOOP;

--raise notice 'Matchs retours';
--raise notice '%', p_date_debut + 7*j + 7*19 -1;
id_arbitre_random := ARRAY(Select id_arbitre
                           From arbitre
                           order by random()
                           limit (10));
                           
--raise notice '%', id_arbitre_random;

date_match = p_date_debut + 7*j + 7*19 -1;

WHILE index_inf < 21 LOOP
	--Insert les matchs retour pour une journée
        insert into rencontre values (
            id_club_random[index_inf],
            id_club_random[index_sup],
            id_arbitre_random[index_sup],
            date_match,
            0,
            0,
            1,
            (j+19)    
            );
    --raise notice '% : %', id_club_random[index_inf], id_club_random[index_sup];
    
    index_inf = index_inf + 1;
    index_sup = index_sup - 1;
END LOOP;

id_last_club_temp = id_club_random[20];

i = 1;

--Modification de l'ordre des équipes
WHILE i <20 LOOP
    id_club_random[20-i+1] = id_club_random[20-i];
    i = i+1;
END LOOP;
id_club_random[2] = id_last_club_temp;

--raise notice '%', id_club_random;
j = j + 1;
END LOOP;

END ;
$$;


ALTER FUNCTION public.init_rencontres(p_annee integer, p_date_debut date, p_id_championnat integer) OWNER TO postgres;

--
-- TOC entry 256 (class 1255 OID 26606)
-- Name: init_saison(integer, integer[], integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.init_saison(p_annee integer, p_tab integer[], p_ligue integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$DECLARE
   backup_table_name text;
   i integer;
   annee_backup integer;

BEGIN 
   -- verif si la saison existe deja et que le tableau contient 20 equipes
   IF (SELECT count(*) FROM saison WHERE annee = p_annee) = 0 AND array_length(p_tab, 1) = 20 THEN
         
	  annee_backup = extract(year from (select date_match from rencontre order by date_match limit 1));

	  -- creer le nom de la table de sauvegarde au format saison_backup_2023
      backup_table_name := 'rencontre_backup_' || annee_backup::text;
  
      -- copie des données de la table qui va être sauvegardé
      EXECUTE 'CREATE TABLE ' || backup_table_name || ' AS SELECT * FROM rencontre';

      -- vider la table 
      TRUNCATE TABLE rencontre;

      -- reset de la table saison avec les nouvelles données
      i = 0;
      FOR i IN 1..array_length(p_tab, 1) LOOP
         INSERT INTO saison VALUES (p_tab[i], p_ligue, p_annee, 0, 0, 0); 
         i = i + 1;
      END LOOP;

      RETURN true;
   ELSE 
      RETURN false;
   END IF;
END;
$$;


ALTER FUNCTION public.init_saison(p_annee integer, p_tab integer[], p_ligue integer) OWNER TO postgres;

--
-- TOC entry 253 (class 1255 OID 26607)
-- Name: updateClassement(date, integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."updateClassement"(datej date, edomicile integer, evisiteur integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
DECLARE
	r rencontre;
BEGIN

SELECT * INTO r FROM rencontre WHERE date_match = dateJ AND id_equipe_domicile = eDomicile AND id_equipe_visiteur = eVisiteur;

IF r.isjoue = 3 THEN
	IF r.score_domicile > r.score_visiteur THEN
		UPDATE saison
		SET nb_buts_marques = nb_buts_marques + r.score_domicile, nb_buts_encaisse = nb_buts_encaisse + r.score_visiteur, nb_points = nb_points + 3
		WHERE id_club = r.id_equipe_domicile;
		
		UPDATE saison
		SET nb_buts_marques = nb_buts_marques + r.score_visiteur, nb_buts_encaisse = nb_buts_encaisse + r.score_domicile
		WHERE id_club = r.id_equipe_visiteur;
	ELSEIF r.score_domicile < r.score_visiteur THEN
		UPDATE saison
		SET nb_buts_marques = nb_buts_marques + r.score_domicile, nb_buts_encaisse = nb_buts_encaisse + r.score_visiteur
		WHERE id_club = r.id_equipe_domicile;
		
		UPDATE saison
		SET nb_buts_marques = nb_buts_marques + r.score_visiteur, nb_buts_encaisse = nb_buts_encaisse + r.score_domicile, nb_points = nb_points + 3
		WHERE id_club = r.id_equipe_visiteur;
	ELSE
		UPDATE saison
		SET nb_buts_marques = nb_buts_marques + r.score_domicile, nb_buts_encaisse = nb_buts_encaisse + r.score_visiteur, nb_points = nb_points + 1
		WHERE id_club = r.id_equipe_domicile;
		
		UPDATE saison
		SET nb_buts_marques = nb_buts_marques + r.score_visiteur, nb_buts_encaisse = nb_buts_encaisse + r.score_domicile, nb_points = nb_points + 1
		WHERE id_club = r.id_equipe_visiteur;
	END IF;
END IF;

END;
$$;


ALTER FUNCTION public."updateClassement"(datej date, edomicile integer, evisiteur integer) OWNER TO postgres;

--
-- TOC entry 254 (class 1255 OID 26608)
-- Name: updateRencontre(date, integer, integer, integer, integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public."updateRencontre"(datej date, edomicile integer, sdomicile integer, evisiteur integer, svisiteur integer, ematch integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
BEGIN

UPDATE rencontre
SET score_domicile = sDomicile, score_visiteur = sVisiteur, isjoue = eMatch
WHERE date_match = dateJ AND id_equipe_domicile = eDomicile AND id_equipe_visiteur = eVisiteur;

END;
$$;


ALTER FUNCTION public."updateRencontre"(datej date, edomicile integer, sdomicile integer, evisiteur integer, svisiteur integer, ematch integer) OWNER TO postgres;

--
-- TOC entry 260 (class 1255 OID 26922)
-- Name: update_classement(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.update_classement(p_annee_saison integer) RETURNS void
    LANGUAGE plpgsql
    AS $$


DECLARE
p_id_club record;
p_nb_matchs_gagne integer = 0;
p_nb_matchs_nul integer = 0;
p_nb_matchs_perdu integer = 0;

BEGIN

	for p_id_club in (select id_club from club) loop	
		p_nb_matchs_gagne = get_nb_match_gagne(p_id_club.id_club);
		p_nb_matchs_nul = get_nb_match_nul(p_id_club.id_club);
		p_nb_matchs_perdu = get_nb_match_perdu(p_id_club.id_club);

		UPDATE saison
		set nb_matchs_gagne = p_nb_matchs_gagne,
 		nb_matchs_nul = p_nb_matchs_nul,
 		nb_matchs_perdu = p_nb_matchs_perdu,
		nb_points = (p_nb_matchs_gagne*3 + p_nb_matchs_nul*1),
		nb_buts_marques = get_nb_buts_pour(p_id_club.id_club),
		nb_buts_encaisse = get_nb_buts_contre(p_id_club.id_club)
		WHERE id_club = p_id_club.id_club
		and annee = p_annee_saison;
	end loop;
	
END;
$$;


ALTER FUNCTION public.update_classement(p_annee_saison integer) OWNER TO postgres;

--
-- TOC entry 255 (class 1255 OID 26609)
-- Name: update_rencontre(integer, integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.update_rencontre(p_id_equipe_domicile integer, p_id_equipe_visiteur integer, p_journee integer) RETURNS void
    LANGUAGE plpgsql
    AS $$

BEGIN
  UPDATE rencontre SET score_domicile = FLOOR(RANDOM() * 5), score_visiteur = FLOOR(RANDOM() * 5), isjoue = 3
  WHERE id_equipe_domicile = p_id_equipe_domicile AND id_equipe_visiteur = p_id_equipe_visiteur AND journee = p_journee;
END;

$$;


ALTER FUNCTION public.update_rencontre(p_id_equipe_domicile integer, p_id_equipe_visiteur integer, p_journee integer) OWNER TO postgres;

--
-- TOC entry 245 (class 1255 OID 26921)
-- Name: update_rencontre_all_journee(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.update_rencontre_all_journee() RETURNS void
    LANGUAGE plpgsql
    AS $$
DECLARE
p_journee record;
p_annee_saison integer;
BEGIN

	for p_journee in (select distinct journee from rencontre order by journee) loop		
		PERFORM update_rencontre_journee(p_journee.journee);
	end loop;
	
	
END;
$$;


ALTER FUNCTION public.update_rencontre_all_journee() OWNER TO postgres;

--
-- TOC entry 259 (class 1255 OID 26610)
-- Name: update_rencontre_journee(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.update_rencontre_journee(p_journee integer) RETURNS void
    LANGUAGE plpgsql
    AS $$

DECLARE
id_equipe record;
p_annee_saison integer;
BEGIN

	for id_equipe in (select id_equipe_domicile, id_equipe_visiteur from rencontre where journee = p_journee) loop
		raise notice '% : %', id_equipe.id_equipe_domicile, id_equipe.id_equipe_visiteur;
		PERFORM update_rencontre(id_equipe.id_equipe_domicile, id_equipe.id_equipe_visiteur, p_journee);
	end loop;
	
	p_annee_saison = extract(year from (select date_match from rencontre order by date_match limit 1));
	PERFORM update_classement(p_annee_saison);
END;


$$;


ALTER FUNCTION public.update_rencontre_journee(p_journee integer) OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 209 (class 1259 OID 26611)
-- Name: arbitre; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.arbitre (
    id_arbitre integer NOT NULL,
    nom character varying(32),
    date_naissance date,
    date_pro date
);


ALTER TABLE public.arbitre OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 26614)
-- Name: arbitre_id_arbitre_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.arbitre_id_arbitre_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.arbitre_id_arbitre_seq OWNER TO postgres;

--
-- TOC entry 3446 (class 0 OID 0)
-- Dependencies: 210
-- Name: arbitre_id_arbitre_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.arbitre_id_arbitre_seq OWNED BY public.arbitre.id_arbitre;


--
-- TOC entry 211 (class 1259 OID 26615)
-- Name: article; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.article (
    id_article integer NOT NULL,
    titre_article character varying(128),
    desc_article text,
    auteur_article character varying(50),
    image_article text
);


ALTER TABLE public.article OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 26620)
-- Name: article_id_article_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.article_id_article_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.article_id_article_seq OWNER TO postgres;

--
-- TOC entry 3447 (class 0 OID 0)
-- Dependencies: 212
-- Name: article_id_article_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.article_id_article_seq OWNED BY public.article.id_article;


--
-- TOC entry 213 (class 1259 OID 26621)
-- Name: championnat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.championnat (
    id_championnat integer NOT NULL,
    nom character varying(32)
);


ALTER TABLE public.championnat OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 26624)
-- Name: championnat_id_championnat_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.championnat_id_championnat_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.championnat_id_championnat_seq OWNER TO postgres;

--
-- TOC entry 3448 (class 0 OID 0)
-- Dependencies: 214
-- Name: championnat_id_championnat_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.championnat_id_championnat_seq OWNED BY public.championnat.id_championnat;


--
-- TOC entry 215 (class 1259 OID 26625)
-- Name: club; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.club (
    id_club integer NOT NULL,
    nom_club character varying(128) NOT NULL,
    id_stade integer,
    ville character varying(32),
    logo text,
    annee_fondation integer,
    president character varying(50),
    entraineur character varying(50)
);


ALTER TABLE public.club OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 26630)
-- Name: club_id_club_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.club_id_club_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.club_id_club_seq OWNER TO postgres;

--
-- TOC entry 3449 (class 0 OID 0)
-- Dependencies: 216
-- Name: club_id_club_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.club_id_club_seq OWNED BY public.club.id_club;


--
-- TOC entry 217 (class 1259 OID 26631)
-- Name: commentaire; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.commentaire (
    id_commentaire integer NOT NULL,
    str_commentaire character varying,
    id_article integer,
    id_uti integer
);


ALTER TABLE public.commentaire OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 26636)
-- Name: commentaire_id_commentaire_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.commentaire_id_commentaire_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.commentaire_id_commentaire_seq OWNER TO postgres;

--
-- TOC entry 3450 (class 0 OID 0)
-- Dependencies: 218
-- Name: commentaire_id_commentaire_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.commentaire_id_commentaire_seq OWNED BY public.commentaire.id_commentaire;


--
-- TOC entry 219 (class 1259 OID 26637)
-- Name: logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.logs (
    id integer NOT NULL,
    id_uti integer,
    date_log timestamp without time zone,
    ip_log character varying(16),
    status boolean
);


ALTER TABLE public.logs OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 26640)
-- Name: logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.logs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.logs_id_seq OWNER TO postgres;

--
-- TOC entry 3451 (class 0 OID 0)
-- Dependencies: 220
-- Name: logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.logs_id_seq OWNED BY public.logs.id;


--
-- TOC entry 221 (class 1259 OID 26641)
-- Name: news; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.news (
    id_news integer NOT NULL,
    id_club integer NOT NULL,
    article_news character varying
);


ALTER TABLE public.news OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 26646)
-- Name: news_id_news_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.news_id_news_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.news_id_news_seq OWNER TO postgres;

--
-- TOC entry 3452 (class 0 OID 0)
-- Dependencies: 222
-- Name: news_id_news_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.news_id_news_seq OWNED BY public.news.id_news;


--
-- TOC entry 223 (class 1259 OID 26647)
-- Name: rencontre; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rencontre (
    id_equipe_domicile integer NOT NULL,
    id_equipe_visiteur integer NOT NULL,
    id_arbitre integer NOT NULL,
    date_match date NOT NULL,
    score_domicile integer,
    score_visiteur integer,
    isjoue integer,
    journee integer
);


ALTER TABLE public.rencontre OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 26925)
-- Name: rencontre_backup_2021; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rencontre_backup_2021 (
    id_equipe_domicile integer,
    id_equipe_visiteur integer,
    id_arbitre integer,
    date_match date,
    score_domicile integer,
    score_visiteur integer,
    isjoue integer,
    journee integer
);


ALTER TABLE public.rencontre_backup_2021 OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 26937)
-- Name: rencontre_backup_2022; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rencontre_backup_2022 (
    id_equipe_domicile integer,
    id_equipe_visiteur integer,
    id_arbitre integer,
    date_match date,
    score_domicile integer,
    score_visiteur integer,
    isjoue integer,
    journee integer
);


ALTER TABLE public.rencontre_backup_2022 OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 26653)
-- Name: s_abonner; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.s_abonner (
    id_uti integer NOT NULL,
    id_club integer NOT NULL
);


ALTER TABLE public.s_abonner OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 26656)
-- Name: saison; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.saison (
    id_club integer NOT NULL,
    id_championnat integer NOT NULL,
    annee integer NOT NULL,
    nb_buts_marques integer DEFAULT 0,
    nb_buts_encaisse integer DEFAULT 0,
    nb_points integer DEFAULT 0,
    nb_matchs_gagne integer DEFAULT 0,
    nb_matchs_nul integer DEFAULT 0,
    nb_matchs_perdu integer DEFAULT 0
);


ALTER TABLE public.saison OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 26659)
-- Name: stade; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.stade (
    id_stade integer NOT NULL,
    nom character varying(128),
    ville character varying(128),
    capacite integer
);


ALTER TABLE public.stade OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 26662)
-- Name: stade_id_stade_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.stade_id_stade_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.stade_id_stade_seq OWNER TO postgres;

--
-- TOC entry 3453 (class 0 OID 0)
-- Dependencies: 227
-- Name: stade_id_stade_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.stade_id_stade_seq OWNED BY public.stade.id_stade;


--
-- TOC entry 228 (class 1259 OID 26663)
-- Name: utilisateur; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.utilisateur (
    id_uti integer NOT NULL,
    id_club integer NOT NULL,
    nom_uti character varying(30) NOT NULL,
    prenom_uti character varying(30) NOT NULL,
    sexe_uti character varying(15) NOT NULL,
    password_uti character varying(64) NOT NULL,
    date_inscription date,
    image_uti text,
    mail_uti text,
    admin_uti boolean DEFAULT false
);


ALTER TABLE public.utilisateur OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 26668)
-- Name: utilisateur_id_uti_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.utilisateur_id_uti_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.utilisateur_id_uti_seq OWNER TO postgres;

--
-- TOC entry 3454 (class 0 OID 0)
-- Dependencies: 229
-- Name: utilisateur_id_uti_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.utilisateur_id_uti_seq OWNED BY public.utilisateur.id_uti;


--
-- TOC entry 3243 (class 2604 OID 26669)
-- Name: arbitre id_arbitre; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.arbitre ALTER COLUMN id_arbitre SET DEFAULT nextval('public.arbitre_id_arbitre_seq'::regclass);


--
-- TOC entry 3244 (class 2604 OID 26670)
-- Name: article id_article; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.article ALTER COLUMN id_article SET DEFAULT nextval('public.article_id_article_seq'::regclass);


--
-- TOC entry 3245 (class 2604 OID 26671)
-- Name: championnat id_championnat; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.championnat ALTER COLUMN id_championnat SET DEFAULT nextval('public.championnat_id_championnat_seq'::regclass);


--
-- TOC entry 3246 (class 2604 OID 26672)
-- Name: club id_club; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.club ALTER COLUMN id_club SET DEFAULT nextval('public.club_id_club_seq'::regclass);


--
-- TOC entry 3247 (class 2604 OID 26673)
-- Name: commentaire id_commentaire; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commentaire ALTER COLUMN id_commentaire SET DEFAULT nextval('public.commentaire_id_commentaire_seq'::regclass);


--
-- TOC entry 3248 (class 2604 OID 26674)
-- Name: logs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logs ALTER COLUMN id SET DEFAULT nextval('public.logs_id_seq'::regclass);


--
-- TOC entry 3249 (class 2604 OID 26675)
-- Name: news id_news; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news ALTER COLUMN id_news SET DEFAULT nextval('public.news_id_news_seq'::regclass);


--
-- TOC entry 3256 (class 2604 OID 26676)
-- Name: stade id_stade; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stade ALTER COLUMN id_stade SET DEFAULT nextval('public.stade_id_stade_seq'::regclass);


--
-- TOC entry 3258 (class 2604 OID 26677)
-- Name: utilisateur id_uti; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateur ALTER COLUMN id_uti SET DEFAULT nextval('public.utilisateur_id_uti_seq'::regclass);


--
-- TOC entry 3260 (class 2606 OID 26715)
-- Name: arbitre pk_arbitre; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.arbitre
    ADD CONSTRAINT pk_arbitre PRIMARY KEY (id_arbitre);


--
-- TOC entry 3262 (class 2606 OID 26717)
-- Name: article pk_article; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.article
    ADD CONSTRAINT pk_article PRIMARY KEY (id_article);


--
-- TOC entry 3264 (class 2606 OID 26719)
-- Name: championnat pk_championnat; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.championnat
    ADD CONSTRAINT pk_championnat PRIMARY KEY (id_championnat);


--
-- TOC entry 3268 (class 2606 OID 26721)
-- Name: club pk_club; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.club
    ADD CONSTRAINT pk_club PRIMARY KEY (id_club);


--
-- TOC entry 3270 (class 2606 OID 26723)
-- Name: commentaire pk_commentaire; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commentaire
    ADD CONSTRAINT pk_commentaire PRIMARY KEY (id_commentaire);


--
-- TOC entry 3272 (class 2606 OID 26725)
-- Name: logs pk_logs; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logs
    ADD CONSTRAINT pk_logs PRIMARY KEY (id);


--
-- TOC entry 3275 (class 2606 OID 26727)
-- Name: news pk_news; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT pk_news PRIMARY KEY (id_news);


--
-- TOC entry 3277 (class 2606 OID 26729)
-- Name: rencontre pk_rencontre; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rencontre
    ADD CONSTRAINT pk_rencontre PRIMARY KEY (id_equipe_domicile, id_equipe_visiteur, id_arbitre, date_match);


--
-- TOC entry 3281 (class 2606 OID 26731)
-- Name: s_abonner pk_s_abonner; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.s_abonner
    ADD CONSTRAINT pk_s_abonner PRIMARY KEY (id_uti, id_club);


--
-- TOC entry 3283 (class 2606 OID 26733)
-- Name: saison pk_saison; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.saison
    ADD CONSTRAINT pk_saison PRIMARY KEY (id_club, id_championnat, annee);


--
-- TOC entry 3285 (class 2606 OID 26735)
-- Name: stade pk_stade; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stade
    ADD CONSTRAINT pk_stade PRIMARY KEY (id_stade);


--
-- TOC entry 3288 (class 2606 OID 26737)
-- Name: utilisateur pk_utilisateur; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT pk_utilisateur PRIMARY KEY (id_uti);


--
-- TOC entry 3266 (class 2606 OID 26739)
-- Name: championnat uk_nom; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.championnat
    ADD CONSTRAINT uk_nom UNIQUE (nom);


--
-- TOC entry 3273 (class 1259 OID 26740)
-- Name: i_fk_news_club; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX i_fk_news_club ON public.news USING btree (id_club);


--
-- TOC entry 3278 (class 1259 OID 26741)
-- Name: i_fk_s_abonner_club; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX i_fk_s_abonner_club ON public.s_abonner USING btree (id_club);


--
-- TOC entry 3279 (class 1259 OID 26742)
-- Name: i_fk_s_abonner_utilisateur; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX i_fk_s_abonner_utilisateur ON public.s_abonner USING btree (id_uti);


--
-- TOC entry 3286 (class 1259 OID 26743)
-- Name: i_fk_utilisateur_club; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX i_fk_utilisateur_club ON public.utilisateur USING btree (id_club);


--
-- TOC entry 3290 (class 2606 OID 26744)
-- Name: commentaire fk_article; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commentaire
    ADD CONSTRAINT fk_article FOREIGN KEY (id_article) REFERENCES public.article(id_article);


--
-- TOC entry 3289 (class 2606 OID 26749)
-- Name: club fk_equipe_stade; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.club
    ADD CONSTRAINT fk_equipe_stade FOREIGN KEY (id_stade) REFERENCES public.stade(id_stade);


--
-- TOC entry 3292 (class 2606 OID 26754)
-- Name: logs fk_logs_utilisateur; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logs
    ADD CONSTRAINT fk_logs_utilisateur FOREIGN KEY (id_uti) REFERENCES public.utilisateur(id_uti);


--
-- TOC entry 3293 (class 2606 OID 26759)
-- Name: news fk_news_club; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT fk_news_club FOREIGN KEY (id_club) REFERENCES public.club(id_club);


--
-- TOC entry 3294 (class 2606 OID 26764)
-- Name: rencontre fk_rencontre_arbitre; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rencontre
    ADD CONSTRAINT fk_rencontre_arbitre FOREIGN KEY (id_arbitre) REFERENCES public.arbitre(id_arbitre);


--
-- TOC entry 3295 (class 2606 OID 26769)
-- Name: rencontre fk_rencontre_equipe_dom; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rencontre
    ADD CONSTRAINT fk_rencontre_equipe_dom FOREIGN KEY (id_equipe_domicile) REFERENCES public.club(id_club);


--
-- TOC entry 3296 (class 2606 OID 26774)
-- Name: rencontre fk_rencontre_equipe_vis; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rencontre
    ADD CONSTRAINT fk_rencontre_equipe_vis FOREIGN KEY (id_equipe_visiteur) REFERENCES public.club(id_club);


--
-- TOC entry 3297 (class 2606 OID 26779)
-- Name: s_abonner fk_s_abonner_club; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.s_abonner
    ADD CONSTRAINT fk_s_abonner_club FOREIGN KEY (id_club) REFERENCES public.club(id_club);


--
-- TOC entry 3298 (class 2606 OID 26784)
-- Name: s_abonner fk_s_abonner_utilisateur; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.s_abonner
    ADD CONSTRAINT fk_s_abonner_utilisateur FOREIGN KEY (id_uti) REFERENCES public.utilisateur(id_uti);


--
-- TOC entry 3299 (class 2606 OID 26789)
-- Name: saison fk_saison_championnat; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.saison
    ADD CONSTRAINT fk_saison_championnat FOREIGN KEY (id_championnat) REFERENCES public.championnat(id_championnat);


--
-- TOC entry 3300 (class 2606 OID 26794)
-- Name: saison fk_saison_equipe; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.saison
    ADD CONSTRAINT fk_saison_equipe FOREIGN KEY (id_club) REFERENCES public.club(id_club);


--
-- TOC entry 3291 (class 2606 OID 26953)
-- Name: commentaire fk_uti; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commentaire
    ADD CONSTRAINT fk_uti FOREIGN KEY (id_uti) REFERENCES public.utilisateur(id_uti) NOT VALID;


--
-- TOC entry 3301 (class 2606 OID 26799)
-- Name: utilisateur fk_utilisateur_club; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT fk_utilisateur_club FOREIGN KEY (id_club) REFERENCES public.club(id_club);


-- Completed on 2023-04-23 20:13:29

--
-- PostgreSQL database dump complete
--

