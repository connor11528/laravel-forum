<template>
	<button type='submit' :class='classes' @click='toggle'>
		<span class='glyphicon glyphicon-heart'></span>
		<span v-text='count'></span>
	</button>
</template>

<script>
export default {
	props: ['reply'],
	data(){
		return {
			// custom attributes on Reply Eloquent model
			count: this.reply.favoritesCount, 
			active: this.reply.isFavorited
		}
	},
	computed: {
		classes(){
			return ['btn', this.active ? 'btn-primary' : 'btn-default'];
		},
		endpoint(){
			return "/replies/" + this.reply.id + "/favorites";
		}
	},
	methods: {
		toggle(){
			this.active ? this.unfavorite() : this.favorite();
		},
		favorite(){
			axios.post(this.endpoint);
			this.active = true;
			this.count += 1;
		},
		unfavorite(){
			axios.delete(this.endpoint);
			this.active = false;
			this.count -= 1;
		}
	}
}
</script>