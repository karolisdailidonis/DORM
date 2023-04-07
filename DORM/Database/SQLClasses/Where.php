<?php
namespace DORM\Database\SQLClasses;

class Where
{
	public $where = null;

	protected $supportedOperators = [
		"="	=> "comparison",
		">" => "comparison",
		"<" => "comparison",
		">=" => "comparison",
		"<=" => "comparison",
		"<>" => "comparison",
		"BETWEEN" => "between",
		"NOTNULL" => "notNull",
		"ISNULL" => "isNull",
		"block" => "block"
	];

	public function __construct(array $where = null)
	{
		$this->where = $where;
	}

	protected function comparison($data): string
	{
		return $data["column"] . " " . $data["condition"] . " '" . $data["value"] . "'"; 
	}

	protected function between($data): string
	{
		return "{$data['column']} BETWEEN '{$data['val1']}' AND '{$data['val2']}'";
	}

	protected function notNull($data): string
	{
		return $data["column"] . " IS NOT NULL"; 
	}

	protected function isNull($data): string
	{
		return $data["column"] . " IS NULL"; 
	}
	
	protected function block($data): string
	{
		$block = "(";

		$data = $data["where"];
		
		$block .= $this->{ $this->supportedOperators[$data[0]["condition"]] }($data[0]);
		
		for ($i = 1; $i < count($data); $i++) {

			if (isset($data[$i]['op'])){
				$block .= ' OR ';
			} else {
				$block .= ' AND ';
			}

			$block .= $this->{$this->supportedOperators[$data[$i]["condition"]]}($data[$i]);
		}

		$block .= ")";

		return $block;
	}

	public function __toString(): string
	{
		if ($this->where == null) { return ''; }
		
		$out = " WHERE ";

		$out .= $this->{$this->supportedOperators[$this->where[0]["condition"]]}( $this->where[0]);
		
		for ($i = 1; $i < count($this->where); $i++) {

			if (isset($this->where[$i]['op'])){
				$out .= ' OR ';
			} else {
				$out .= ' AND ';
			}

			$out .= $this->{$this->supportedOperators[$this->where[$i]["condition"]]}($this->where[$i]);
		}

		return $out;
	}
}