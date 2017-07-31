<script>
export default {
	props: ['attributes'],
	data(){
		return {
			editing: false,
			body: this.attributes.body 	// passed in the reply from Laravel
		};
	},
	methods: {
		update(){
			axios.patch("/replies/" + this.attributes.id, {
				body: this.body
			});

			this.editing = false;
			flash('Your reply was updated');
		},
		destroy(){
			axios.delete("/replies/" + this.attributes.id);

			// old school jQuery fadeOut
			$(this.$el).fadeOut(300, () => {
				flash('Your reply has been deleted');
			});
		}
	}
}
</script>