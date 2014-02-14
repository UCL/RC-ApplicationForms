RC-ApplicationForms
===================

Web stuff to handle UCL RC application process.

(At time of writing this document contains a bunch of schema docs which should be moved into the actual schema sql as comments, so excuse any confusion.)

## Design

As currently designing, there are 5 entry points (forms) for interacting with the database:

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

We use this to determine the quantity of funding that was allocated to projects using RCPS services, so we want the grant quantity, which we can get from the award number. This can also be obtained using the Grant Code, but there's currently a question about the virtues of obtaining Grant Codes vs Award Numbers. As each Grant Code only has 1 Award Number, it is possible to uniquely determine the grant from the Grant Code as well as the Award Number. The concern is which users are more likely to be able to find out reliably -- Award Numbers are less visible to everyday users, but Grant Codes are easily confused with Grant Ledger Codes.

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

#### Policy

 * Who should approve service requests
 * In how much detail we want to record collaborator information (e.g. multiple collaborators from other institutions)
 * Whether to record grant information at initial sign-up (or just annually retrospectively)

#### Design

 * How exactly to record submission and approval (or lack thereof)
 * How to inform people about renewal requirements


