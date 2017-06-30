<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{

	protected $request;

	// Eloquest builder
	protected $builder;

	// Register filters to operate on
	protected $filters = [];

	// create a new ThreadFilters instance
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	// Apply the filters
	public function apply($builder)
	{
		$this->builder = $builder;

		foreach ($this->getFilters() as $filter => $value){
			if(method_exists($this, $filter)){
				$this->$filter($value);
			}
		}

		return $this->builder;
	}

	// Fetch all relevant filters from the request
	public function getFilters()
	{
		return $this->request->intersect($this->filters);
	}
}