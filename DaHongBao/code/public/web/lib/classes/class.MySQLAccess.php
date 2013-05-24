<?php

# MySQL_Access.php - provide a MySQL database-access abstraction

# Paul DuBois
# paul@kitebird.com
# 2000-02-08

# 2000-11-29
# - Modify error behavior to allow termination-on-error behavior to be
#   turned off.
# - Set errno to -1 for errors that are not MySQL-related (no MySQL error
#   codes are negative).

#@ _OUTLINE_1_
class MySQLAccess
{
	var $host_name = "";
	var $user_name = "";
	var $password = "";
	var $db_name = "";
	var $conn_id = 0;
	var $errno = 0;
	var $errstr = "";
	var $halt_on_error = 1;
	var $query_pieces = array ();
	var $result_id = 0;
	var $num_rows = 0;
	var $row = array ();
#@ _OUTLINE_1_


# If $halt_on_error is set, print a message and die.
# Otherwise silently return so caller can return an error
# code to its caller.

#@ _ERROR_
function error ($msg)
{
	if (!$this->halt_on_error)	# return silently
		return;
	$msg .= "\n";
	if ($this->errno)	# if an error code is known, include error info
		$msg .= sprintf ("Error: %s (%d)\n", $this->errstr, $this->errno);
	die (nl2br (htmlspecialchars ($msg)));
}
#@ _ERROR_


# Test for an active connection; try to establish one if none exists.
# The connection parameters should have been set before invoking
# this routine.  Normally, there is no need to call it explicitly,
# because issue_query() does so automatically.  This means you can
# start issuing queries whevever you want without first connecting to
# the server as long as you've set the connection parameters properly.

# Return the connection ID for a successful connection.  If a
# connection cannot be established or the database cannot be selected,
# this calls error(), which terminates the script if $halt_on_error isn't
# disabled.

#@ _CONNECT_
function connect ()
{
	$this->errno = 0;			# clear the error variables
	$this->errstr = "";
	if ($this->conn_id == 0)	# connect if not already connected
	{
		$this->conn_id = @mysql_connect ($this->host_name,
											$this->user_name,
											$this->password);
		# use mysql_errno()/mysql_error() if they work for
		# connection errors; use $php_errormsg otherwise
		if (!$this->conn_id)
		{
			# mysql_errno() returns nonzero if it's
			# functional for connection errors
			if (mysql_errno ())
			{
				$this->errno = mysql_errno ();
				$this->errstr = mysql_error ();
			}
			else
			{
				$this->errno = -1;	# use alternate values
				$this->errstr = $php_errormsg;
			}
			$this->error ("Cannot connect to server");
			return (FALSE);
		}
		# select database if one has been specified
		if (isset ($this->db_name) && $this->db_name != "")
		{
			if (!@mysql_select_db ($this->db_name, $this->conn_id))
			{
				$this->errno = mysql_errno ();
				$this->errstr = mysql_error ();
				$this->error ("Cannot select database");
				return (FALSE);
			}
		}
	}
	return ($this->conn_id);
}
#@ _CONNECT_

#@ _DISCONNECT_
function disconnect ()
{
	if ($this->conn_id != 0)	# there's a connection open; close it
	{
		mysql_close ($this->conn_id);
		$this->conn_id = 0;
	}
	return (TRUE);
}
#@ _DISCONNECT_


# Helper function that escapes special characters in the value, and
# adds quotes surrounding the value. Special case: for unset values,
# return the string NULL without surrounding quotes.

#@ _QUOTE_
function sql_quote ($str)
{
	if (!isset ($str))
		return ("NULL");
	$func = function_exists ("mysql_escape_string")
			? "mysql_escape_string"
			: "addslashes";
	return ("'" . $func ($str) . "'");
}
#@ _QUOTE_


# Prepare a query.  Placeholders should be indicated by ? characters.

#@ _PREPARE_QUERY_
function prepare_query ($query)
{
	$this->query_pieces = explode ("?", $query);
	return (TRUE);
}
#@ _PREPARE_QUERY_


# Send a query to the database server.

# Return the result ID for a successful query.  If the query fails, and
# $halt_on_error is disabled, return FALSE.

# If the argument is a string, execute it directly.
# If the argument is an array, interpret its elements as values to be
# bound to a previously prepared query.  There must be one data value
# per placeholder.

#@ _ISSUE_QUERY_
function issue_query ($arg = "")
{
	if ($arg == "")				# if no argument, assume prepared statement
		$arg = array ();		# with no values to be bound
	if (!$this->connect ())		# establish connection to server if
		return (FALSE);			# necessary

	if (is_string ($arg))		# $arg is a simple query
		$query_str = $arg;
	else if (is_array ($arg))	# $arg contains data values for placeholders
	{
		if (count ($arg) != count ($this->query_pieces) - 1)
		{
			$this->errno = -1;
			$this->errstr = "data value/placeholder count mismatch";
			$this->error ("Cannot execute query");
			return (FALSE);
		}
		# insert data values into query at placeholder 
		# positions, quoting values as we go
		$query_str = $this->query_pieces[0];
		for ($i = 0; $i < count ($arg); $i++)
		{
			$query_str .= $this->sql_quote ($arg[$i])
						. $this->query_pieces[$i+1];
		}
	}
	else						# $arg is garbage
	{
		$this->errno = -1;
		$this->errstr = "unknown argument type to issue_query";
		$this->error ("Cannot execute query");
		return (FALSE);
	}

	$this->num_rows = 0;
	$this->result_id = mysql_query ($query_str, $this->conn_id);
	$this->errno = mysql_errno ();
	$this->errstr = mysql_error ();
	if ($this->errno)
	{
		$this->error ("Cannot execute query: $query_str");
		return (FALSE);
	}
	# get number of affected rows for non-SELECT; this also returns
	# number of rows for a SELECT
	$this->num_rows = mysql_affected_rows ($this->conn_id);
	return ($this->result_id);
}
#@ _ISSUE_QUERY_


# Close the result set, if there is one

#@ _FREE_RESULT_
function free_result ()
{
	if ($this->result_id)
		mysql_free_result ($this->result_id);
	$this->result_id = 0;
	return (TRUE);
}
#@ _FREE_RESULT_


# Return the next row of the result set as an associative array,
# numeric-index array, or an object.
# Return FALSE when no more rows are left.

#@ _FETCH_ARRAY_
# Fetch the next row as an array with numeric and named indexes

function fetch_array ()
{
	$this->row = mysql_fetch_array ($this->result_id);
	$this->errno = mysql_errno ();
	$this->errstr = mysql_error ();
	if ($this->errno)
	{
		$this->error ("fetch_array error");
		return (FALSE);
	}
	if (is_array ($this->row))
		return ($this->row);
	$this->free_result ();
	return (FALSE);
}
#@ _FETCH_ARRAY_

#@ _FETCH_ROW_
# Fetch the next row as an array with numeric indexes

function fetch_row ()
{
	$this->row = mysql_fetch_row ($this->result_id);
	$this->errno = mysql_errno ();
	$this->errstr = mysql_error ();
	if ($this->errno)
	{
		$this->error ("fetch_row error");
		return (FALSE);
	}
	if (is_array ($this->row))
		return ($this->row);
	$this->free_result ();
	return (FALSE);
}
#@ _FETCH_ROW_

#@ _FETCH_OBJECT_
# Fetch the next row as an object

function fetch_object ()
{
	$this->row = mysql_fetch_object ($this->result_id);
	$this->errno = mysql_errno ();
	$this->errstr = mysql_error ();
	if ($this->errno)
	{
		$this->error ("fetch_object error");
		return (FALSE);
	}
	if (is_object ($this->row))
		return ($this->row);
	$this->free_result ();
	return (FALSE);
}
#@ _FETCH_OBJECT_


# Return the AUTO_INCREMENT value generated by the most recent query

function get_insert_id ()
{
	return (mysql_insert_id ($this->conn_id));
}


# Return the number of columns in the current result set

function get_num_fields_count ()
{
	return (mysql_num_fields ($this->result_id));
}


# Return the i-th column info structure for the current result set

function fetch_field ($i)
{
	return (mysql_fetch_field ($this->result_id, $i));
}

# Getters

function getHostName() {
  return $this->host_name;
}

function getUserName() {
  return $this->user_name;
}

function getDB() {
  return $this->db_name;
}

function getPassword() {
  return $this->password;
}

#@ _OUTLINE_2_
} # end MySQL_Access
#@ _OUTLINE_2_

?>
