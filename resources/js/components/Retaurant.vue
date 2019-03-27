<template>
	<div>
		<form @submit.prevent="addArticle" class="mb-3 form-inline">
			<!-- <div class="form-group">
				<input type="text" name="" class="form-control" placeholder="title"
					v-model="article.title">
			    <button class="btn btn-light btn-block" type="submit"> Submit </button>
			</div> -->

             <div class="form-group mx-sm-10 col-md-10">
                <label for="restaurant_name" class="sr-only"> Restaurant name </label>
                <input type="text" class="form-control" id="restaurant_name" placeholder="name" style="width:100% ">
            </div>
            <button type="submit" class="btn btn-primary col-md-2"> Submit </button>
		</form>

        <div class="container">
            <div class="row">
                <div class="col-md-4 ">
                    <h3> List </h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"> <a href="#"> Cras justo odio </a> </li>
                        <li class="list-group-item"> <a href="#"> Dapibus ac facilisis in </a> </li>
                        <li class="list-group-item"> <a href="#"> Morbi leo risus </a>  </li>
                        <li class="list-group-item"> <a href="#"> Porta ac consectetur ac </a> </li>
                        <li class="list-group-item"> <a href="#"> Vestibulum at eros </a> </li>
                    </ul>
                </div>
                <div class="col-md-8">
                    <h3> Location </h3>
                    <div class="google-map" :id="mapName"></div>
                       
                </div>

            </div>
        </div>

	</div>
</template>
<script>
export default {
  name: 'google-map',
  props: ['name'],
  data: function () {
    return {
      mapName: this.name + "-map",
      markerCoordinates: [{
        latitude: 7.057645,
        longitude: 125.579810
      }],
      map: null,
      bounds: null,
      markers: []
    }
  },
  mounted: function () {
    this.bounds = new google.maps.LatLngBounds();
    const element = document.getElementById(this.mapName)
    const mapCentre = this.markerCoordinates[0]
    const options = {
      center: new google.maps.LatLng(mapCentre.latitude, mapCentre.longitude)
    }
    this.map = new google.maps.Map(element, options);
    this.markerCoordinates.forEach((coord) => {
      const position = new google.maps.LatLng(coord.latitude, coord.longitude);
      const marker = new google.maps.Marker({ 
        position,
        map: this.map
      });
    this.markers.push(marker)
      this.map.fitBounds(this.bounds.extend(position))
    });
  }
};
</script>
<style scoped>
.google-map {
  width: 100%;
  height: 600px;
  margin: 0 auto;
  background: gray;
}
</style>