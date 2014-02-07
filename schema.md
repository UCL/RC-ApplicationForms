
# Speculative DB Schema

; Consortia
: id
: full_name
: short_name

; Privileged Users
: id
: username

; Consortium Permissions
: id
: username (fkey)
: can_approve_consortium (fkey)

; Req Progress
: id
: req_id (fkey)
: event class id (fkey)
: note
: date

; Event Classes
: id
: class

; Acct Req
: id
: user_id
: date

; Project
: id
: user_id
: added_with_req_id (fkey)
: grant_code
: is_funded
: pi_user_id
: consortium_id (fkey)
: wants_legion
: wants_iridis
: wants_emerald
: why_wants_cfi
: cfi_impact
: cfi_usage
: collab_bristol
: collab_bristol_name
: collab_oxford
: collab_oxford_name
: collab_soton
: collab_soton_name
: collab_other
: collab_other_institution
: collab_other_institution_name

