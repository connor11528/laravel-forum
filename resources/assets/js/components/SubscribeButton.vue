<template>
	<div class='btn btn-default' @click='subscribe'>Subscribe</div>
</template>

<script>
import axios from 'axios'

export default {
	data(){
		return {}
	},
	methods: {
		subscribe(){
			axios.post(`${location.pathname}/subscriptions`)
				.then((res)=>{
					console.log(res);
					flash('Subscribed to thread')
				})
		}
	}
}

</script>