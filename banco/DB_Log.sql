#Estrutura da tabela log

use brgxh0ghbxt8p6ivcxtc;

create table log(
	id_log integer auto_increment primary key,
    usuario varchar(15) not null,
    comando varchar(500) default '',
    dtcria timestamp default current_timestamp
);

select * from log