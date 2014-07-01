RC-ApplicationForms
===================

[![Build Status](https://travis-ci.org/UCL/RC-ApplicationForms.svg?branch=master)](https://travis-ci.org/UCL/RC-ApplicationForms)

Web stuff to handle UCL RC application process.

## Design

As currently designing, there are 5 entry points

* initial application - `apply.php`
* view of users - `view_user.php`
* view of project requests - `view_project_requests.php`
* submitting information about grants and publications - `publications.php`
* renewal *(not yet started)*

There are two additional navigable pages to which users will be exposed:

* handling post data from `apply.php` - `submit_application.php`
* handling post data from `publications.php` - `submit_publications.php`


Classes are in `classes/`, non-class includes are in `includes/`, SQL files that handle the setup are in `sql/` and
should probably be used to make a sort of `initialise.php` script.

Forms to be included in other pages are in `forms/`, and email templates are in `templates/`. `views/` contains
snippets for displaying data from classes.

Configuration information like database names and passwords are in `includes/config.php` as static class properties,
because using globals was behaving oddly and I didn't have the spoons left at the time to work it out.

