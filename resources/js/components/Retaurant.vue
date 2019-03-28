<template>
	<div>
		<!-- <form @submit.prevent="addArticle" class="mb-3 form-inline"> -->

       <!-- <div class="form-group mx-sm-10 col-md-10">
          <label for="restaurant_name" class="sr-only"> Restaurant name </label>
          <input type="text" class="form-control" id="restaurant_name" v-model="search" placeholder="name" style="width:100% ">
      </div> -->
            <!-- <button type="submit" class="btn btn-primary col-md-2"> Submit </button> -->
		<!-- </form> -->

        <div class="container">
            <div class="row">
                <div class="col-md-4 ">
                <!--     <h3> Search: </h3> -->
                  <div class="form-group">
                    <label for="restaurant_name" class="sr-only"> Restaurant name </label>
                    <input type="text" class="form-control" id="restaurant_name" v-model="search" placeholder="name" style="width:100% ">
                  </div>
                  <div class="form-group">
                    <h3> List </h3>
                    <span class="fa fa-plus" id="add_resto" @click="addNewResto()"></span>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" v-for="item in filteredRestaurants"  :key="item.id" @click="loadnewCoordinates(item.id)"> <a href="#"> {{ item.name }} </a> </li>
                    </ul>
                  </div>
                </div>
                <div class="col-md-8">                 
                    <div class="form-group">
                      <h3> Map </h3>
                      <div class="google-map" :id="mapName"></div>
                    </div>   
                    <div class="form-group" v-if="isSelected">
                        <!-- <h3>Restaurant Details</h3> -->
                        <div class="row">
                          <div class="col-md-8">
                            <h5> {{ selectedResto.name }} </h5>
                            <label> <b> Address :</b> <span>{{ selectedResto.address }} </span> </label><br>
                            <label> <b>Category :</b> <span> {{ selectedResto.Category }}</span></label><br>
                            <label> <b>Specialty :</b> <span> {{ selectedResto.Specialty }} </span></label><br>
                            <label> <b>Open Hours :</b> <span> {{ selectedResto.openHourse }} </span></label>
                          </div>
                          <div class="col-md-4" id="sales_div">
                            <h5> Sales </h5>
                              <label> <b>Daily :</b> <span> {{ selectedResto.daily }}</span></label><br>
                              <label> <b>Monthly :</b> <span>{{ selectedResto.monthly }} </span> </label>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

	</div>
</template>
<script>
import bootbox from 'bootbox'
export default {
  name: 'google-map',
  props: ['name'],
  data: function () {
    return {
      mapName: this.name + "-map",
      search: '',
      markerCoordinates: [],
      map: null,
      bounds: null,
      markers: [],
      restoList: [],
      isSelected: false,
      selectedResto: []
    }
  },
  computed:
  {
      filteredRestaurants:function()
      {
          const self=this;
          const data = this.restoList.filter(function(cust){return cust.name.toLowerCase().indexOf(self.search.toLowerCase())>=0;});
          return data;
      }
  },
  methods: {
    loadResto(){
        axios.get('/api/restaurant')
          .then(res => {
            this.restoList = res.data
            setTimeout(() => {
              this.loadCoordinates(res.data);
            }, 100);
            
          }).catch(err => {
          console.log(err)
        })
    },
    loadCoordinates(data){
      const newData = data

      if(data.length){
         data.map(obj =>{ 
          this.markerCoordinates.push({latitude: obj.latitude, longitude: obj.longitude})
        })
      }else{
        this.markerCoordinates = [];
        this.markerCoordinates.push({latitude: newData.latitude, longitude: newData.longitude})
      }

      // this.bounds = new google.maps.LatLngBounds();
      // const element = document.getElementById(this.mapName)
      // const mapCentre = this.markerCoordinates[0]
      // const options = {
      //   center: new google.maps.LatLng(mapCentre.latitude, mapCentre.longitude),
      //   zoom: 10
      // }
      // this.map = new google.maps.Map(element, options);
      // this.markerCoordinates.forEach((coord) => {
      //   const position = new google.maps.LatLng(coord.latitude, coord.longitude);
      //   const marker = new google.maps.Marker({ 
      //     position,
      //     map: this.map
      //   });
      // this.markers.push(marker)
      //   this.map.fitBounds(this.bounds.extend(position))
      // });
      
    },
    loadnewCoordinates(id){
      const newCoordinates = this.restoList.find(obj => obj.id == id);
      this.loadCoordinates(newCoordinates)
      this.selectedResto = newCoordinates
      this.isSelected = true;

    },
    addNewResto(){
      const data  = `
          <form>
            <div class="form-group">
              <label for="rs_name"> Restaurant Name </label>
              <input type="text" class="form-control" id="rs_name">
              <small class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
              <label for="rs_category"> Category </label>
              <input type="text" class="form-control" id="rs_category">
              <small class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
              <label for="rs_specialty"> Specialty </label>
              <input type="text" class="form-control" id="rs_specialty">
              <small class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
              <label for="rs_contact"> Contact: </label>
              <input type="text" class="form-control" id="rs_contact">
              <small class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
              <label for="rs_email"> Email </label>
              <input type="text" class="form-control" id="rs_email">
              <small class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
              <label for="rs_address"> Address </label>
              <input type="text" class="form-control" id="rs_address">
              <small class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
              <label for="rs_openhrs"> Open Hours </label>
              <input type="text" class="form-control" id="rs_openhrs">
            </div>  
            <div class="form-group">
              <label for="exampleInputPassword1"> Coordinates </label>
              <div class="form-inline">
                <input type="number" class="form-control" id="rs_latitude" placeholder="latitude">
                <input type="number" class="form-control" id="rs_longitude" placeholder="longitude">
              </div>
            </div>

          </form>`;  

      bootbox.confirm({
          title: "New Restaurant",
          message: data,
          buttons: {
              cancel: {
                  label: '<i class="fa fa-times"></i> Cancel'
              },
              confirm: {
                  label: '<i class="fa fa-check"></i> Confirm'
              }
          },
          callback: function (result) {
              console.log('This was logged in the callback: ' + result);
          }
      });
    }
  },
  created(){
    this.loadResto()
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
  span#add_resto {
      float: right;
      margin-top: -31px;
      color: green;
  }
  div#sales_div {
    margin-top: 38px;
  }
  input#rs_longitude {
    margin-left: 35px;
  }

  input#rs_longitude {
      margin-left: 33px;
  }

</style>
