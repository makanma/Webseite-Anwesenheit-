drop table anwesendheit;

create table if not exists mitarbeiter(
	id_mitarbeiter serial primary key,
	vorname text,
	nachname text
	
);

create table if not exists anwesenheit(
	datum date,
	anwesend bool default false,
	fk_id_mitarbeiter int,
	constraint fk_anwesenheit
		foreign key(fk_id_mitarbeiter)
			references mitarbeiter(id_mitarbeiter) ON DELETE CASCADE

);
	
ALTER TABLE anwesenheit ADD FOREIGN KEY (fk_id_mitarbeiter)
REFERENCES mitarbeiter(id_mitarbeiter) ON DELETE CASCADE;


create table if not exists tage(
	id serial primary key,
	datum date unique
);

insert into tage (datum) values('2021-04-26');


insert into mitarbeiter (vorname, nachname) values ('James','Turner');
insert into mitarbeiter (vorname, nachname) values ('Robert','Smith');
insert into mitarbeiter (vorname, nachname) values ('David','Morgan');

select generateDate()

create or replace function generateDate()
	returns void
	LANGUAGE plpgsql
as $$
declare
	datum1 date;
	rec int;
begin
	
	select datum into datum1 from tage order by id desc limit 1;

	if(now()>datum1) then
		insert into tage (datum) values (now());
		--raise notice '%',rec;
		FOR rec IN
        	SELECT id_mitarbeiter from mitarbeiter
    	loop
        	INSERT INTO anwesenheit (datum,anwesend, fk_id_mitarbeiter) VALUES (now(),false,rec);
    	END LOOP;
	end if;

	EXCEPTION WHEN OTHERS then 
			null;
end;
$$;

select anwesend from anwesenheit a where datum = '2021-04-26' and fk_id_mitarbeiter=1;

select getAnwesenheit('2021-04-26',2);


create or replace function getAnwesenheit(date1 date, fk int)
	returns bool
	LANGUAGE plpgsql
as $$
declare
	anw bool;
begin
	select anwesend into anw from anwesenheit where datum = date1 and fk_id_mitarbeiter=fk;
	return anw;
end;
$$;

update anwesenheit set anwesend='t' where datum = '2021-04-26' and fk_id_mitarbeiter = 1

select switchAnwesenheit('2021-04-26',1);

create or replace function switchAnwesenheit(date1 date, id int)
	returns void
	LANGUAGE plpgsql
as $$
declare
	anw bool;
begin
	select anwesend into anw from anwesenheit where datum = date1 and fk_id_mitarbeiter=id;
	

	if(anw='t')then
		anw='f';
	else
		anw='t';
	end if;
	
	raise notice '%', anw;
	update anwesenheit set anwesend=anw where datum = date1 and fk_id_mitarbeiter = id;
end;
$$;

CLUSTER tage USING tage_pkey
CLUSTER mitarbeiter USING mitarbeiter_pkey

DELETE FROM mitarbeiter WHERE id_mitarbeiter=4;

select i_id, rtxt_vorname, rtxt_nachname from f_create_new('gtgt','asdasd');

drop function f_create_new;
create or replace function f_create_new(ptxt_first_name text, ptxt_last_name text)
returns table(i_id text, rtxt_vorname text, rtxt_nachname text)
language plpgsql
as $$
declare
	user_id int4;
begin
	insert into mitarbeiter (vorname, nachname) values (ptxt_first_name, ptxt_last_name) returning id_mitarbeiter into user_id::text;
	return query
		SELECT user_id::text,ptxt_first_name,ptxt_last_name;
		
end;
$$;

delete from anwesenheit where fk_id_mitarbeiter = 2;
delete from mitarbeiter where id_mitarbeiter = 2;

create or replace function f_delete(id text)
returns void
language plpgsql
as $$
declare

begin
	delete from anwesenheit where fk_id_mitarbeiter = id::int;
	delete from mitarbeiter where id_mitarbeiter = id::int;
end;
$$;
