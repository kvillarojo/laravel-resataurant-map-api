<template>
	<div>
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
              <span class="fa fa-plus" id="add_resto" @click="addNewResto()"
              ></span>
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
                      <label> <b>Category :</b> <span> {{ selectedResto.category }}</span></label><br>
                      <label> <b>Specialty :</b> <span> {{ selectedResto.specialty }} </span></label><br>
                      <label> <b>Open Hours :</b> <span> {{ selectedResto.open_hrs }} </span></label>
                    </div>
                    <div class="col-md-4" id="sales_div">
                      <h5> Sales </h5>
                        <label> <b>Daily :</b> <span> {{ selectedResto.daily_sales }}</span></label><br>
                        <label> <b>Monthly :</b> <span>{{ selectedResto.monthly_sales }} </span> </label>
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
      selectedResto: [],
      category: [],
      specialty: []
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
            this.restoList = res.data.resto
            this.category = res.data.categories
            this.specialty = res.data.specialty
            // console.log(this.restoList);
            // setTimeout(() => {
            //   this.loadCoordinates(res.data.resto);
            // }, 100);
            
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
      const self = this;

      const data  = `
          <form>
            <div class="form-group">
              <label for="rs_name"> Restaurant Name </label>
              <input type="text" class="form-control" id="rs_name">
            </div>
            <div class="form-group">
              <label for="rs_category"> Category </label>
              <select class="form-control" id="rs_category"></select>
            </div>
            <div class="form-group">
              <label for="rs_specialty"> Specialty </label>
              <select class="form-control" id="rs_specialty"></select>
            </div>
            <div class="form-group">
              <label for="rs_address"> Address </label>
              <input type="text" class="form-control" id="rs_address">
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="rs_monhtlySales"> Monthly Sales </label>
                  <input type="number" class="form-control" id="rs_monhtlySales">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="rs_dailySales"> Daily Sales </label>
                  <input type="number" class="form-control" id="rs_dailySales">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="rs_openhrs"> Open Hours </label>
              <input type="text" class="form-control" id="rs_openhrs">
            </div>  
            <div class="form-group">
              <label for="exampleInputPassword1"> Coordinates </label>
              <div class="form-inline">
                <input type="number" class="form-control" id="rs_latitude" placeholder="latitude">
                <input type="number" class="form-control" id="rs_longitude" placeholder="longitude" style="margin-left:34px">
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
                  label: '<i class="fa fa-check" @click="saveResto()"></i> Confirm'
              }
          },
          callback: function (result) {
              const data = {
                name: $('#rs_name').val(),
                category: $('#rs_category').val(),
                specialty: $('#rs_specialty').val(),
                address: $('#rs_address').val(),
                monthly_sales: $('#rs_monhtlySales').val(),
                daily_sales: $('#rs_dailySales').val(),
                open_hrs: $('#rs_openhrs').val(),
                latitude: $('#rs_latitude').val(),
                longitude: $('#rs_longitude').val(),
              }
             if (result) {
                self.saveResto(data);
             }
          }
      });

      this.category.map(obj => {
        $('#rs_category').append(`<option value="`+ obj.id +`">`+ obj.name +`</option>`)
      })

      this.specialty.map(obj => {
        $('#rs_specialty').append(`<option value="`+ obj.id +`">`+ obj.name +`</option>`)
      })      
      
    },
    saveResto(data){
      axios.post('/api/restaurant', data)
        .then(res => {

            let localData = data;
            localData.category =  $('#rs_category option:selected').text();
            localData.specialty = $('#rs_specialty option:selected').text();
            this.restoList.push(localData);

        }).catch(err => {
        console.log(err)
      })
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
</style>
