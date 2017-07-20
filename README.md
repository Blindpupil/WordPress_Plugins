# WordPress_Plugins
A very simple example plugin, and a job listing plugin

The example plugin declutters a bit the dashboard and adds a link to google analytics in the admin bar. 

The job listings plugin contains a custom post type for jobs and provides a couple of shortcodes. 
The job_location_list indexes all the locations that have current job openings. 
The jobs_by_location shortcode takes in some parameters (pagination, count, title, location) 
to return a list of clickable job listings according to those parameters using wp_query, 
and to customize the pagination. 

There’s also some jQuery used reorder the list of jobs and save that order in the database. 
