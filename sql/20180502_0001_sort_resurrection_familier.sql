
INSERT INTO public.sorts (sort_combinaison, sort_nom, sort_fonction, sort_cout, 
		sort_comp_cod, sort_distance, sort_description, sort_aggressif, 
		sort_niveau, sort_soi_meme, sort_monstre, sort_joueur, sort_soutien, 
		sort_bloquable, sort_case, sort_temps_recharge)
VALUES ('231444', 'Résurrection de familier', 'magie_resurrection_fam', 12, 
		51, 0, 'Ce puissant sortilège qui ne peut être mémorisé vous permet de pouvoir faire revenir votre dernier familier du plan des morts à la position où vous vous situez au moment du lancement du sortilège.',
		'N', 6, 'O', 'N', 'N', 'N', 'N', 'N', 0);
		
INSERT INTO sort_rune(srune_sort_cod,srune_gobj_cod) 
select 175,gobj_cod from objet_generique where gobj_rune_position = 2 and gobj_frune_cod = 1
UNION 
select 175,gobj_cod from objet_generique where gobj_rune_position = 3 and gobj_frune_cod = 2
UNION
select 175,gobj_cod from objet_generique where gobj_rune_position = 1 and gobj_frune_cod = 3
UNION
select 175,gobj_cod from objet_generique where gobj_rune_position = 4 and gobj_frune_cod = 4
UNION
select 175,gobj_cod from objet_generique where gobj_rune_position = 4 and gobj_frune_cod = 5
UNION
select 175,gobj_cod from objet_generique where gobj_rune_position = 4 and gobj_frune_cod = 6 ;
