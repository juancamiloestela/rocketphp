<?php 
/**
 * This class has been autogenerated by RocketPHP
 */

namespace Resources;

class Tag extends \Rocket\Api\Resource{

	protected $fields = array("text","posts");
	protected static $notExposed = array("");

	function receive_text($value, &$errors) {
		$errors = array_merge($errors, $this->validate_text($value));
		return $value;
	}

	function validate_text($value) {
		$errors = array();
		if (!is_string($value)){ $errors[] = "Tag.text.incorrectType.string"; }
		if (strlen($value) > 30){ $errors[] = "Tag.text.tooLong"; }
		if (strlen($value) < 3){ $errors[] = "Tag.text.tooShort"; }
		return $errors;
	}

	function receive_posts($value, &$errors) {
		$errors = array_merge($errors, $this->validate_posts($value));
		return $value;
	}

	function validate_posts($value) {
		$errors = array();
		return $errors;
	}

	function posts($id) {
		// TODO: return query here so that users can customize result eg. LIMIT, ORDER BY, WHERE x, etc
		$query = "SELECT Post.* FROM Post JOIN posts_tags ON Post.id = posts_tags.posts_id WHERE posts_tags.tags_id = :id";
		$statement = $this->db->prepare($query);
		$statement->execute(array('id' => $id));
		$data = $statement->fetchAll(\PDO::FETCH_ASSOC);
		
		// TODO: $data = customHook($data);
		return $data;
	}

	function GET_tags_when_public($data) {
		$errors = array();

		\Rocket::call(array("ResponseTime", "on_start"), $data);
		if (count($errors)) {
			throw new \InvalidInputDataException($errors);
		}

		\Rocket::call(array("paginated", "on_input"), $data);
		$query = "SELECT * FROM Tag";
		\Rocket::call(array("paginated", "on_query"), $query, $data);
		$statement = $this->db->prepare($query);
		$statement->execute( $this->getDataForQuery($query, $data) );
		$data = $statement->fetchAll(\PDO::FETCH_ASSOC);
		\Rocket::call(array("ResponseTime", "on_data"), $data);
		\Rocket::call(array("paginated", "on_data"), $data);
		return $data;
	}

	function GET_tags_id_when_public($data, $id) {
		$errors = array();

		$data["id"] = $id;

		\Rocket::call(array("ResponseTime", "on_start"), $data);
		if (count($errors)) {
			throw new \InvalidInputDataException($errors);
		}

		$query = "SELECT * FROM Tag WHERE id = :id LIMIT 1";
		$statement = $this->db->prepare($query);
		$statement->execute( $this->getDataForQuery($query, $data) );
		$data = $statement->fetch(\PDO::FETCH_ASSOC);
		if (!$data){
			throw new \NotFoundException();
		}
		\Rocket::call(array("ResponseTime", "on_data"), $data);
		return $data;
	}

	function GET_tags_id_posts_when_public($data, $id) {
		$errors = array();

		$data["id"] = $id;

		\Rocket::call(array("ResponseTime", "on_start"), $data);
		if (count($errors)) {
			throw new \InvalidInputDataException($errors);
		}

		$data = $this->posts($id);
		\Rocket::call(array("ResponseTime", "on_data"), $data);
		return $data;
	}

	function GET_tagged_tag_when_public($data, $tag) {
		$errors = array();

		$data["tag"] = $tag;

		\Rocket::call(array("ResponseTime", "on_start"), $data);
		if (count($errors)) {
			throw new \InvalidInputDataException($errors);
		}

		$query = "SELECT * FROM Tag WHERE id = :id LIMIT 1";
		\Rocket::call(array("Tags", "on_query"), $query, $data);
		$statement = $this->db->prepare($query);
		$statement->execute( $this->getDataForQuery($query, $data) );
		$data = $statement->fetch(\PDO::FETCH_ASSOC);
		if (!$data){
			throw new \NotFoundException();
		}
		\Rocket::call(array("ResponseTime", "on_data"), $data);
		return $data;
	}

	function GET_tagged_tag_posts_when_public($data, $tag) {
		$errors = array();

		$data["tag"] = $tag;

		\Rocket::call(array("ResponseTime", "on_start"), $data);
		if (count($errors)) {
			throw new \InvalidInputDataException($errors);
		}

		$data = $this->posts($id);
		\Rocket::call(array("ResponseTime", "on_data"), $data);
		return $data;
	}

}