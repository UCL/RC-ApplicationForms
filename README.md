RC-ApplicationForms
===================

[![Build Status](https://travis-ci.org/UCL/RC-ApplicationForms.svg?branch=master)](https://travis-ci.org/UCL/RC-ApplicationForms)

Web stuff to handle UCL RC application process.

This page assumes you know the difference between a POST and a GET HTTP request.

## Design

As currently designing, there are 4 entry points (forms) for interacting with the database:

* initial application - `apply.php`
* single view - `list.php`
* list of many requests *(not yet implemented)*
* renewal *(not yet implemented)*

There is also one visible file that is used for `apply.php` to submit POST data to - `submit.php`.

Other, non-visible files include:

* `sqlactor.php` - handles all the SQL requests
* `mailmailer.php` - handles all mail sending and templating
* `header.php` and `footer.php` - are included to create the UCL page style
* `misc_functions.php` - little bitty functions that didn't fit anywhere else
* `auth_user_shim.php` - a shim to handle the dev environment where I didn't have httpd auth set up

### Initial Application - `apply.php`

An initial application creates an **Account_Request** (in the database, not on any service), and a Project. The information is gathered by `apply.php`'s form, and then the form's POST posts the data to `submit.php`. `submit.php` uses SQLActor methods to put that data into the database, adds a status update to say that the request has been submitted, and then mails the relevant consortium leaders.

There's some magic in the HTML form name values to create nicely arranged arrays in the POST PHP superglobal variable, which is used to distinguish between project and account request data. There's also some Javascript to make handy hidden compiled information fields for collaboration and work required -- these might have been a dumb idea, but they're also somewhat convenient. They get used in the notification emails. The other bit of Javascript is to make sure that you can't submit the form unless you've agreed to the Ts and Cs.

Because of the way information is POSTed, the method in SQLActor that submits to the db has to sort out checkbox information -- checkboxes that aren't checked don't get submitted with a FALSE value or anything, they just don't get submitted. The code goes through a list of checkboxes that should match the ones originally in the form, and checks whether they're set or not, constructing a new 1/0 array.

SQLActor returns an array containing the ids of the new entries in the database, so they can be used in the notification emails.

### View Application - `list.php`

Maybe I should refactor the name of this one.

This shows a single request pair (account request + project), along with its most recent status change.

If the currently logged-in user has permission, this view also contains an approval form, that allows you to approve or decline the quest with a text area for comments. This POSTs to the same page, which then handles marking the request pair approved in the db via SQLActor. It is also possible to approve or decline requests by supplying an "action=approve" or "action=decline" GET variable -- in this case, the comments section will be set to "(via a link)" or something similar.

### SQL-using functions - `sqlactor.php`

The SQLActor class handles all the database manipulation. Because I also made it at the same time as `apply.php`, it also might handle slightly more data manipulation and reworking than it should, but it's probably not a big deal. If it involves executing SQL, it's in here. This file also contains the database configuration information, in the `SQLActor::connect()` method. There isn't a particularly good reason for this.

### Mail Templating and Sending - `mailmailer.php`

The MailMailer class contains a tiny templating engine, and sends mail. It has an array of template names, with the subject lines, reply-to addresses if not rc-support, and a call to grab the body of the template from a file. At the moment, these are all named `template_name.txt`, but they don't have to be. These are then processed to replace values of the form `{:name}` with the corresponding value from a provided array, or `{:name.index}` to sub-index, for example this would get `$info['name']['index']`.

This is then sent to all members of an array of email addresses.

It's simple and pretty generic.

### List *(not yet implemented)*

This should present a list of anything (project and service requests) the logged-in user has the authority to approve (with approval links), or has submitted themselves. It also needs to contain approved requests, so that a user can submit additional services for a project.


### Renewal *(not yet implemented)*

**It is currently unclear how renewal or requests for renewal should be triggered.**

The renewal form collects any award numbers for new awards for grants for projects that are using RCPS services, as well as publications based on work performed using RCPS services.

We use this to determine the quantity of funding that was allocated to projects using RCPS services, so we want the grant quantity, which we can get from the award number. This can also be obtained using the Grant Code, but there's currently a question about the virtues of obtaining Grant Codes vs Award Numbers. As each Grant Code only has 1 Award Number, it is possible to uniquely determine the grant from the Grant Code as well as the Award Number. The concern is which users are more likely to be able to find out reliably -- Award Numbers are less visible to everyday users, but Grant Codes are easily confused with Grant Ledger Codes.


### Database

Because of this structure:

* Account Requests (maybe rename to just accounts) have Projects
* Projects have service requests
* Account requests contain user information
* Projects contain project, PI, and funding information
* Account requests also have publications
* Any approval or submission status change information is stored in a separate table, with timestamps, usernames, and comments



### Current Questions

#### Policy

 * Which information to obtain annually

#### Design

 * How to inform people about renewal requirements
