RC-ApplicationForms
===================

Web stuff to handle UCL RC application process.

(At time of writing this document contains a bunch of schema docs which should be moved into the actual schema sql as comments, so excuse any confusion.)

## Design

As my brainmeats are currently designing, there are 5 entry points (forms) for interacting with the database:

* initial application
* renewal
* service request
* list
* single view

### Initial Application

An initial application creates an account (in the database, not on any service), as well as a number of associated project requests. Project requests also have one or more associated requests for service accounts.

There is currently a question of whether consortium leaders or RCPS members will approve service requests, but consortium leaders will approve at least project requests.

### Renewal

**It is currently unclear how renewal or requests for renewal should be triggered.**

The renewal form collects any award numbers for new awards for grants for projects that are using RCPS services, as well as publications based on work performed using RCPS services.


### Service Request

The service request form acts on a project to request an additional service. As above, it is currently unclear as to whether these should be approved by consortium leaders or RCPS members.

### List

This should present a list of anything (project and service requests) the logged-in user has the authority to approve (with approval links), or has submitted themselves. It also needs to contain approved requests, so that a user can submit additional services for a project.

### Single View

This should show all the information associated with a single account, including projects, project requests, accessible services, etc.

If the currently logged-in user has permission, this view should also contain an approval link.


### Database

Because of this structure:

* Account Requests (maybe rename to just accounts) have projects
* Projects have service requests
* Account requests contain user information
* Projects contain project and funding information
* Service requests contain justification if necessary
* Account requests also have publications
* Any approval or submission information is stored in the events table



### Current Questions

I'm not entirely convinced that the information about submission and approval needs to be stored in a separate table -- I could have anything that requires approval have fields recording who a thing was approved by and when.

----




## Schema

There are a number of tables that are just lists of things to be referred to by other tables, so I'll list them first.

; Consortia
: The short name should be the name of the consortium on Legion -- e.g. TYCNano for the equivalent full-name "Thomas Young Centre - Nanoscience"
; Event Types
: Is just a list of possible events that can happen to a request -- e.g. submitted, approved, etc.
; User Types
: This should store categories of users, designed to refer to e.g. Research Staff, Postgraduate Student, Undergraduate Student, etc.
; User Experience Levels
: Should contain the list of possible experience levels users can report. Expected to contain something like ('Novice user with no identified support', 'Novice user with identified support', ...)
; Services
: A list of services users can apply for accounts on. Expected to contain "Legion", "Iridis", "Emerald" or something similar.

#### Nontrivial Tables

##### Privileged Users

This table holds all users who have special levels of access, e.g. consortium leaders can approve requests. It also holds whether the user is a superuser, to avoid having to have an entry of every type for those users.

| Row | Definition |
|:---:|:----------:|
| id                                   | just an index key                                  |
| username                             | UCL username e.g. ccaaxxx                          |
| full_name                            | User's name                                        |
| super_special_rainbow_pegasus_powers | Whether the user is a superuser                    |
| receives_emails                      | Whether the username can receive/respond to emails |
| email_address                        | Their email address                                |

##### Account Requests

| Row | Definition |
|:---:|:----------:|
| id                      | just an index key                                |
| username                | username for account                             |
| user_upi                | UPI for account                                  |
| supervisor_upi          | user's supervisor                                |
| user_type_id            | what category from user_types the user fits into |
| user_email_address      | user's email address                             |
| user_surname            | user's surname                                   |
| user_forenames          | all user's forenames                             |
| user_forename_preferred | user's preferred forename                        |
| user_contact_number     | contact telephone number for user                |
| user_dept               | Department user is in                            |
| user_experience_id      | what category from user_experience_levels        |
| user_experience         | text description of previous hpc experience      |


##### Account Request Progress

| Row | Definition |
|:---:|:----------:|
| id            | just an index key                                     |
| request_id    | key into account requests table                       |
| event_type_id | key into event_types, to say what kind of event it is |
| acting_user   | the user who instigated this event                    |
| object TEXT   | arbitrary description field                           |
| update_time   | timestamp for the event                               |


##### Projects

| Row | Definition |
|:---:|:----------:|
| id                              | just an index key
| username                        | unnecessary duplicate of username field for simplicity?               |
| request_id                      | key into account requests table                                       |
| award_number                    | something grant-related that Clare wanted                             |
| is_funded                       | whether the project has funding                                       |
| pi_username                     | the username of the PI?                                               |
| consortium_id                   | key into the consortia table                                          |
| is_collab_bristol               | whether the project is a collaboration with Bristol                   |
| collab_bristol_person           | who the project is a collaboration with                               |
| is_collab_oxford                | whether the project is a collaboration with Oxford                    |
| collab_oxford_person            | who the project is a collaboration with                               |
| is_collab_soton                 | whether the project is a collaboration with Soton                     |
| collab_soton_person             | who the project is a collaboration with                               |
| is_collab_other                 | whether the project is a collaboration with a different institution   |
| collab_other_institution        | which institution the project is a collaboration with                 |
| collab_other_institution_person | who the project is a collaboration with                               |


##### Service Requests

A request for a service account for a project.

| Row | Definition |
|:---:|:----------:|
| id                | just an index key                                                     |
| project_id        | key into projects table                                               |
| service_id        | key into services table                                               |
| wants_cfi_because | arbitrary text entered to explain why user wants CfI resource         |
| cfi_impact        | arbitrary text entered to waffle about how important your research is |
| cfi_usage         | how the person plans to use the service if they have any clue         |

##### Publications

Publications arising from our services, updated annually.

| Row | Definition |
|:---:|:----------:|
| id         | just an index key                                          |
| account_id | key into account_requests table                            |
| url        | link to publication                                        |
| notable    | is this research the most special research ever performed? |

##### Publication Services

Each row records a service a publication used, in terms of ids from the services table.

| Row | Definition |
|:---:|:----------:|
| id              | just an index key           |
| publications_id | key into publications table |
| service_used    | key into services table     |


