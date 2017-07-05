<?php 

namespace App;

trait RecordsActivity 
{
	// Boot the trait
	// https://tighten.co/blog/laravel-tip-bootable-model-traits
	protected static function bootRecordsActivity()
	{
		if (auth()->guest()) return;

		foreach (static::getActivitiesToRecord() as $event) {
			static::$event(function($model) use ($event){
				$model->recordActivity($event);
			});
		}

		static::deleting(function($model){
			$model->activity()->delete();
		});
	}

	// Fetch all model events that require activity recording
	protected static function getActivitiesToRecord()
	{
		return ['created'];
	}

	// Record new activity for the model
	protected function recordActivity($event)
	{
		$this->activity()->create([
			'user_id' => auth()->id(),
			'type' => $this->getActivityType($event)
		]);
	}

	// Fetch the activity relationship
	protected function activity()
	{
		// https://laravel.com/docs/5.4/eloquent-relationships#polymorphic-relations
		return $this->morphMany('App\Activity', 'subject');
	}

	// Determine the activity type
	protected function getActivityType($event)
	{
		$type = strtolower((new \ReflectionClass($this))->getShortName());
		return "{$event}_{$type}";
	}
}