<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CI-Service Test Page</title>
	<link type="text/css" rel="stylesheet" href="../css/styles.bootswatch.css" />
  <link type="text/css" rel="stylesheet" href="../css/styles.overrides.css" />
</head>
<body class="container-fluid">
	<h1>Task Manager</h1>
	<dl>
    <dt>Status</dt>
    <dd class="alert alert-success">no known issues</dd>
  </dl>
  <p>Note: These make use of a globally-defined variable <code>api</code> which you should define as follows:</p>
  <pre>var api = "/api/index.php/";</pre>
  <table class="table table-bordered">
  	<thead>
			<tr>
				<th>Endpoint</th>
				<th>Methods available</th>
				<th>Sample Call</th>
				<th>Sample Response</th>
				<th>Notes</th>
			</tr>
    </thead>
    <tbody>

			<!-- CATEGORY -->
      <tr>
        <td>Category</td>
        <td>All</td>
				<td>
<strong>POST a new category</strong>
<pre>$.ajax(api + "category/", {
  type: "POST",
  data: {
    name: "My First List",
    owner: 1
  },
  success: function(data) {
    // Stuff with response...
  }
});</pre>

<strong>GET a specific category:</strong>
<pre>$.get(api + "category/" + categoryID, function(data) {
  // Response script...
});</pre>

<strong>GET categories for a user</strong>
<pre>$.ajax(api + "category/", {
  type: "GET",
  data: {
    filter: "owner=1"
  },
  success: function(data) {
    // Stuff with response...
  }
});</pre>

<strong>PUT updates to a category (change its name)</strong>
<pre>$.ajax(api + "category/" + categoryID, {
  type: "PUT",
  data: {
    name: "My Amazing List"
  },
  success: function(data) {
    // Stuff with response...
  }
});</pre>

<strong>DELETE updates a category</strong>
<pre>$.ajax(api + "category/" + categoryID, {
  type: "DELETE",
  success: function(data) {
    // Stuff with response...
  }
});</pre>
				</td>
				<td>
<strong>GET a single category or response to POST and PUT:</strong>
<pre>{
  "id":"2",
  "name":"My Amazing List"
}</pre>
<strong>GET all categories for a given user:</strong>
<pre>{
  "categories": [
    {
      "id":"2",
      "name":"My Amazing List"
    },
    ...
  ]
}</pre>
				</td>
        <td>
					<h3>POST Details</h3>
					<p>A valid category needs a name and an owner&mdash;the id of the current user. If successful, the category will be returned along with its auto-generated `id` field. Ther `owner` field is ommitted from the returned data set.</p>
					<h3>GET Details</h3>
					<h4>Filtering</h4>
          <p>Only categories for a certain user may be retrieved so <strong>an `owner` filter is required</strong>.</p>
					<dl>
            <dt>owner</dt>
            <dd>Filters to get categories for a particular user: <code>"owner=1"</code></dd>
          </dl>
					<h4>Sorting</h4>
          <p>Sorted by `name` field.</p>
					<h4>Return Value</h4>
					<p>Since we're only permitted to return a list of categories owned by a specific user, we omit the `owner` field in the return set.</p>
					<h3>PUT Details</h3>
					<p>Only category's name can be changed. Be sure to pass its `categoryID` in the URL request.</p>
					<h3>DELETE Details</h3>
					<p>Deleting a category will <strong><em>also delete all the tasks in that category.</em></strong></p>
        </td>
      </tr>

			<!-- TASK -->
      <tr>
	      <td>Task</td>
	      <td>All</td>
				<td>
<strong>POST a new task</strong>
<pre>$.ajax(api + "task/", {
  type: "POST",
  data: {
    owner: 2,
    description: "Finish homework",
    dueDate: "2017-12-01",
    category: 3,
    taskType: 1,
    status: 0
  }
  success: function(data) {
    // Stuff with response...
  }
});</pre>
<strong>GET a list of all tasks for a user</strong>
<pre>$.ajax(api + "task/", {
  type: "GET",
  data: {
    filter: "category=2"
  }
  success: function(data) {
    // Stuff with response...
  }
});</pre>
<strong>GET a specific task</strong>
<pre>$.get(api + "task/" + taskID, function(data) {
  // Stuff with response...
});</pre>
<strong>PUT changes to a task</strong>
<pre>$.ajax(api + "task/", {
  type: "PUT",
  data: {
    description: "Finish homework for Scripting",
    dueDate: "2017-12-02",
    category: 3,
    taskType: 3,
    status: 0
  }
  success: function(data) {
    // Stuff with response...
  }
});</pre>
<strong>DELETE a task</strong>
<pre>$.ajax(api + "task/", {
  type: "DELETE",
  success: function(data) {
    // Stuff with response...
  }
});</pre>
				</td>
				<td>
<strong>GET list of tasks</strong>
<pre>{
  "tasks": [
    {
      "owner": {
        "id": 1,
        "name": "Phil Schanely",
        "email": philschanely@example.com"
      },
      "description": "Finish homework",
      "dueDate": "2017-12-01",
      "category": {
        "id": 3,
        "name": "Schoolwork"
      },
      "taskType": {
        "id":"3",
        "name":"Backburner",
        "order":"3",
        "alias":"type-back"
      },
      "status": 0
    },
    ...
  ]
}</pre>
<strong>GET a single task</strong>
<pre>{
  "owner": {
    "id": 1,
    "name": "Phil Schanely",
    "email": philschanely@example.com"
  },
  "description": "Finish homework",
  "dueDate": "2017-12-01",
  "category": {
    "id": 3,
    "name": "Schoolwork"
  },
  "taskType": {
    "id":"3",
    "name":"Backburner",
    "order":"3",
    "alias":"type-back"
  },
  "status": 0
}</pre>
				</td>
	      <td>
	        <h3>GET Details</h3>
	        <h4>Filtering</h4>
	        <p>Task lists will always be filtered by the current user as `owner` and may also be filtered by category.</p>
					<dl>
						<dt>owner</dt>
						<dd>The id of the current user.</dd>
						<dt>category</dt>
						<dd>The id of a particular category.</dd>
					</dl>
					<h4>Sorting</h4>
	        <p>Results are always sorted by `dueDate` field.</p>
					<h4>Return value</h4>
					<p>Returns a list of all tasks that match any filtering criterion provided.</p>
	      </td>
	    </tr>

			<!-- TASK_TYPE -->
      <tr>
	      <td>Task Type</td>
	      <td>GET</td>
				<td>
<strong>GET a list of task types</strong>
<pre>$.get(api + "task_type/", function(data) {
  // Stuff with response...
});</pre>
				</td>
				<td>
<strong>Returns a list of task all types</strong>
<pre>{
  "task_types": [
    {
      "id":"1",
      "name":"Normal",
      "order":"1",
      "alias":"type-norm"
    },
    {
      "id":"2",
      "name":"Urgent",
      "order":"2",
      "alias":"type-urg"
    },
    {
      "id":"3",
      "name":"Backburner",
      "order":"3",
      "alias":"type-back"
    }
  ]
}</pre>
				</td>
	      <td>
	        <h3>GET Details</h3>
	        <h4>Filtering</h4>
	        <p>No filtering included.</p>
					<h4>Sorting</h4>
	        <p>Results are always sorted by `order` field.</p>
					<h4>Return value</h4>
					<p>Returns a list of all task types available in the system.</p>
	      </td>
	    </tr>

			<!-- USER -->
			<tr>
				<td>User</td>
				<td>GET and POST</td>
				<td>
<strong>GET a user given a know userID:</strong>
<pre>$.get(api + "user/" + userID, function(data) {
  // Response script...
});</pre>

<strong>POST a new user:</strong>
<pre>$.ajax(api + "user/", {
  type: "POST",
  data: {
    "name":"Phil Schanely",
    "email":"philschanely@example.com",
    "password":"encrypted_password_string*"
  },
  success: function(data) {
    // Response script...
  }
});</pre>
				</td>
				<td>
<strong>Returns a User object for POST and GET</strong>
<pre>{
  "id":"1",
  "name":"Phil Schanely",
  "email":"philschanely@example.com"
}</pre>
				</td>
				<td>
					<h3>POST Details</h3>
					<p>Passwords stored in the database should be stored as encrypted strings
						rather than straight equivalents. Therefore, before sending a new user's
						data the password they provide should be encrypted first. The encrypted
						value should be sent <em>NOT</em> the original submitted value.</p>
					<h3>GET Details</h3>
					<h4>Filtering</h4>
					<p>No filtering included.</p>
					<h4>Sorting</h4>
					<p>No sorting necessary for user request.</p>
					<h4>Sorting</h4>
					<p>No sorting included.</p>
					<h4>Return Value</h4>
					<p>A User object is returned containing all properties except the user's password.</p>
				</td>
			</tr>

			<!-- USER AUTHENTICATION -->
			<tr>
	      <td>User Authentication</td>
				<td>GET</td>
				<td>
<strong>GET a user given an email and encrypted password:</strong>
<pre>$.ajax(api + "auth_user/", {
  type: "GET",
  filter: {
    "email": "philschanely@example.com",
    "password": "encrypted_password_string*"
  },
  success: function(data) {
    // Response script...
  }
});</pre>
				</td>
				<td>
<strong>Returns an authentication object:</strong>
<pre>{
  authenticated: true | false,
  user: null | {
    id: 1,
    name: "Phil Schanely",
    email: "philschanely@example.com"
  }
}</pre>
			  </td>
				<td>
					<h3>GET Details</h3>
					<p>The password stored in the database is encrypted. Therefore when a
						user provides their password in the application login form it should be
						encrypted first and then passed here. <em>DO NOT</em> send the original
						submitted password to the service.</p>
					<h4>Filtering</h4>
					<p>An email and encrypted password must be sent as filter parameters.</p>
					<dl>
						<dt>email<dt>
						<dd>The email for the user trying to log in.</dd>
						<dt>password</dt>
						<dd>An encrypted password based on the password the user provided while trying to log in.</dd>
					</dl>
					<h4>Sorting</h4>
					<p>No sorting included</p>
					<h4>Returned Value</h4>
					<p>GET a user if valid `user_email` and `user_password` are provided.</p>
					<p>If an invalid email and password pair is provided then the data
						object that is return will have a `false` value for `authenticated`
						and a `null` value for `user`. Otherwise, `authenticated` will
						be `true` and the user's details will be present in `user`
						except for their password.</p>
				</td>
			</tr>

    </tbody>
  </table>
</body>
</html>
