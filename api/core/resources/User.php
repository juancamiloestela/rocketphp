<?php 
/**
 * This class has been autogenerated by RocketPHP
 */

namespace Resources;

class User extends \Rocket\Api\Resource{

	protected $fields = array("name","email","password","blog");
	protected static $notExposed = array("");

	function receive_name($value, &$errors) {
		$errors = array_merge($errors, $this->validate_name($value));
		return $value;
	}

	function validate_name($value) {
		$errors = array();
		if (!is_string($value)){ $errors[] = "name.incorrectType.string"; }
		if (strlen($value) > 30){ $errors[] = "name.tooLong"; }
		if (strlen($value) < 3){ $errors[] = "name.tooShort"; }
		return $errors;
	}

	function receive_email($value, &$errors) {
		$errors = array_merge($errors, $this->validate_email($value));
		return $value;
	}

	function validate_email($value) {
		$errors = array();
		if (!filter_var($value, FILTER_VALIDATE_EMAIL)){ $errors[] = "email.incorrectType.email"; }
		if (strlen($value) > 30){ $errors[] = "email.tooLong"; }
		if (strlen($value) < 5){ $errors[] = "email.tooShort"; }
		return $errors;
	}

	function receive_password($value, &$errors) {
		$errors = array_merge($errors, $this->validate_password($value));
		return $value;
	}

	function validate_password($value) {
		$errors = array();
		if (!is_string($value)){ $errors[] = "password.incorrectType.string"; }
		if (strlen($value) > 60){ $errors[] = "password.tooLong"; }
		if (strlen($value) < 60){ $errors[] = "password.tooShort"; }
		return $errors;
	}

	function receive_blog($value, &$errors) {
		$errors = array_merge($errors, $this->validate_blog($value));
		return $value;
	}

	function validate_blog($value) {
		$errors = array();
		return $errors;
	}

	function blog($id) {
		// TODO: return query here so that users can customize result eg. LIMIT, ORDER BY, WHERE x, etc
		$query = "SELECT * FROM Blogs WHERE id = :id";
		$statement = $this->db->prepare($query);
		$statement->execute(array('id' => $id));
		$data = $statement->fetch(\PDO::FETCH_ASSOC);
		
		// TODO: $data = customHook($data);
		return $data;
	}

	function GET_users_when_public($data) {
		$errors = array();

		\Rocket::call(array("ResponseTime", "on_start"), $data);
		if (count($errors)) {
			throw new \InvalidInputDataException($errors);
		}

		\Rocket::call(array("paginated", "on_input"), $data);
		$query = "SELECT id,name,email FROM User";
		\Rocket::call(array("paginated", "on_query"), $query, $data);
		$statement = $this->db->prepare($query);
		$statement->execute( $this->getDataForQuery($query, $data) );
		$data = $statement->fetchAll(\PDO::FETCH_ASSOC);
		\Rocket::call(array("ResponseTime", "on_data"), $data);
		\Rocket::call(array("paginated", "on_data"), $data);
		return $data;
	}

	function GET_users_id_when_public($data, $id) {
		$errors = array();

		$data["id"] = $id;

		\Rocket::call(array("ResponseTime", "on_start"), $data);
		if (count($errors)) {
			throw new \InvalidInputDataException($errors);
		}

		$query = "SELECT id,name FROM User WHERE id = :id LIMIT 1";
		$statement = $this->db->prepare($query);
		$statement->execute( $this->getDataForQuery($query, $data) );
		$data = $statement->fetch(\PDO::FETCH_ASSOC);
		if (!$data){
			throw new \NotFoundException();
		}
		\Rocket::call(array("ResponseTime", "on_data"), $data);
		return $data;
	}

}