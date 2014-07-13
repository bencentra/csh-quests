csh-quests
==========

Random "quest" picker for CSH. Spinner made with SVG and JavaScript, the rest with PHP and MySQL.

API
---

I've added a JSON API for getting, adding, editing, and deleting quests. Requests can be made to `api/quests/<method>`.

There is currently no API key or authentication, but Webauth prevents non-CSHers from using it; they can't even reach the API.

The following methods are supported:

### GET api/quests/get

__Description:__ Get a list or subset of all quests in the database, sorted by date added.


__Parameters:__

param | description
--- | ---
limit | How many quests to return (optional, defaults to all quests)
offset | How many entries to skip (optional, used only if limit provided)

__Example Response__:  

```json
{
  "status":true,
  "message":"Success (quests.get)",
  "data":[
    ...
    {
      "id":"14",
      "ts":"2013-06-13 19:31:04",
      "name":"Copdogapaloozathon",
      "info":"Find out about the Copdogapaloozathon event, the movie \"Cop Dog,\" and the host of the event."
    },
    ...
  ]
}
```

### GET api/quests/get/random

__Description:__ Get a list or subset of all quests in the database, returned in random order.

__Parameters:__

param | description
--- | ---
limit | How many quests to return (optional, defaults to all quests)
offset | How many entries to skip (optional, used only if limit provided)

__Example Response__:  

```json
{
  "status":true,
  "message":"Success (quests.get)",
  "data":[
    ...
    {
      "id":"14",
      "ts":"2013-06-13 19:31:04",
      "name":"Copdogapaloozathon",
      "info":"Find out about the Copdogapaloozathon event, the movie \"Cop Dog,\" and the host of the event."
    },
    ...
  ]
}
```

### POST api/quests/add

__Description:__ Add a new quest to the database.


__Parameters:__

param | description
--- | ---
name | The name of the quest _(required)_
info | A short description of the quest  _(required)_

__Example Response__:  

```json
{
  "status":true,
  "message":"Success (quests.add)",
  "data":true
}
```

### POST api/quests/edit

__Description:__ Edit an existing quest in the database


__Parameters:__

param | description
--- | ---
id | The id of the quest to edit _(required)_
name | The name of the quest (optional)
info | A short description of the quest (optional)

__Example Response__:  

```json
{
  "status":true,
  "message":"Success (quests.edit)",
  "data":true
}
```

### POST api/quests/remove

__Description:__ Remove a quest from the database


__Parameters:__

param | description
--- | ---
id | The id of the quest to remove _(required)_

__Example Response__:  

```json
{
  "status":true,
  "message":"Success (quests.remove)",
  "data":true
}
```
