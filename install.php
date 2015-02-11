<?php

namespace eBloodBank;

require 'config.php';
require 'loader.php';

create_database();
create_tables();
insert_user_admin();

redirect( get_site_url() );