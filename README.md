ShareaboutsWP
-------------

ShareaboutsWP is a simple mapping tool for gathering online input about places, inspired by it's big bro Shareabouts (https://github.com/openplans/shareabouts). ShareaboutsWP is a WordPress Theme that runs on PostgreSQL. Spatial database FTW!

Installation
------------
1) PG4WP
PostgreSQL is required for ShareaboutsWP's spatial queries. To get WordPress running on PostgreSQL, see: http://wordpress.org/extend/plugins/postgresql-for-wordpress/

2) Activate Theme
After installing WordPress via PG4WP, when you activate the ShareaboutsWP theme, it will query the database and then add a column "location" with the geometric data type "point" to the $wpdb->posts table if the column does not already exist. 

3) Done! 
Yup, that's all there is to it. 