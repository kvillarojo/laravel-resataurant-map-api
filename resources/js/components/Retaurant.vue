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

              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"> Details </a>
                </li>
                <li class="nav-item" v-if="false">
                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"> <i class="fa fa-cart-plus"></i> Menu </a>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="form-group">
                      <br>
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
                              <label> <b>Daily :</b> <span> {{ daily_sales }}</span></label><br>
                              <label> <b>Monthly :</b> <span>{{ monthly_sales }} </span> </label>
                          </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                      <div class="col-md-12" style="margin-top:25px"> 
                        <h4>Menu</h4>
                        <div class="form-inline" style="float:right; margin-top: -34px; font-size: 21px;">
                          <button id="add_to_cart" class="btn btn-primary"> <i class="fa fa-cart-plus"> </i> </button>
                          <button id="add_menu" class="btn btn-success" @click="createNewMenu()"> <i class="fa fa-plus" style="color:green"></i> </button>
                        </div>
                      </div>
                    </div>
                    <div class="row">

                      <div class="col-sm-12 col-sm-8 col-md-4">
                          <div class="card" style="width: 100%;">
                            <div class="card-body">
                              <h5 class="card-title"> </h5>
                              <h6 class="cadr-subtitle mb-2 text-muted"> Card subtitle </h6>
                              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                              <a href="#" class="card-link"> Add to cart </a>
                            </div>
                          </div>
                      </div>

                       <div class="col-sm-12 col-sm-8 col-md-4">
                          <div class="card" style="width: 100%;">
                            <div class="card-body">
                              <h5 class="card-title"> </h5>
                              <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                              <a href="#" class="card-link"> Add to cart </a>
                            </div>
                          </div>
                      </div>

                      <div class="col-sm-12 col-sm-8 col-md-4">
                          <div class="card" style="width: 100%;">
                            <div class="card-body">
                              <h5 class="card-title"> </h5>
                              <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                              <a href="#" class="card-link"> Add to cart </a>
                            </div>
                          </div>
                      </div>
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
      specialty: [],
      setID: null,
      daily_sales: 0,
      monthly_sales: 0
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
            // console.log(this.restoList)

            this.category = res.data.categories
            this.specialty = res.data.specialty
            // console.log(this.restoList);
            setTimeout(() => {
              this.loadCoordinates(res.data.resto);
            }, 100);
            
          }).catch(err => {
          console.log(err)
        })
    },
    loadCoordinates(data){
      const self = this
      const newData = data

      if(data.length){
         data.map(obj =>{ 
          this.markerCoordinates.push({latitude: obj.latitude, longitude: obj.longitude, name: obj.name, address: obj.address, special: obj.specialty, category: obj.category})
        })
      }else{
        this.markerCoordinates = [];
        this.markerCoordinates.push({latitude: newData.latitude, longitude: newData.longitude,  name: newData.name, address: newData.addresss, special: newData.specialty, category: newData.category})
      }

      this.bounds = new google.maps.LatLngBounds();
      const element = document.getElementById(this.mapName)
      const mapCentre = this.markerCoordinates[0]
      const options = {
        center: new google.maps.LatLng(mapCentre.latitude, mapCentre.longitude),
        zoom: 4
      }

      this.map = new google.maps.Map(element, options);
      this.markerCoordinates.forEach((coord) => {
        const position = new google.maps.LatLng(coord.latitude, coord.longitude);
        const marker = new google.maps.Marker({ 
          position,
          map: this.map,
          title: coord.name
        });
        
        const contentString = 
          `<div id="content">
            <div id="siteNotice">
            </div>
            <h4 id="firstHeading" class="firstHeading">` + coord.name  +`</h4>
            <div id="bodyContent">
             <p><strong>Special:</strong> `+  coord.special + `</p>
             <p><strong>Category: </strong>`+  coord.category + `</p><br>
            <p><strong>Latiude:  </strong>`+ coord.latitude + ` <span style="float: right; margin-left:10px"> <strong>Longitude: </strong> ` + coord.longitude + ` </p>
            </div>
        </div>`;  

        const infowindow = new google.maps.InfoWindow({
          content: contentString
        });

        marker.addListener('mouseover', function() {       
            infowindow.open(this.map, marker);
        });

        marker.addListener('mouseout', function() {       
            infowindow.close();
        });

      this.markers.push(marker)
        this.map.fitBounds(this.bounds.extend(position))
      });  
      
    },
    loadnewCoordinates(id){
      this.setID = id
      const newCoordinates = this.restoList.find(obj => obj.id == id);
      this.loadCoordinates(newCoordinates)
      this.selectedResto = newCoordinates
      this.isSelected = true;
      this.getSales(id);
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
    },
    createNewMenu(){
      const self = this;

      const data  = `
          <form>
            <div class="form-group">
              <label for="rs_menu"> Item Name </label>
              <input type="text" class="form-control" id="rs_menu">
            </div>
            <div class="form-group">
              <label for="rs_category"> Category </label>
              <select name="menu_category" class="form-control" id="rs_category">
                <option id="0"> Drinks </option>
                <option id="1"> Appetizer </option>
                <option id="2"> Fish and Shellfish </option>
                <option id="3"> Meat and Poultry </option>
                <option id="4"> Side Dish </option>
                <option id="5"> Cheese </option>
              </select>
            </div>
            <div class="form-group">
              <label for="rs_price"> Price </label>
              <input type="number" class="form-control" id="rs_price">
            </div>
          </form>`;  

      bootbox.confirm({
          title: "Add Item",
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
                name: $('#rs_menu').val(),
                menu_category: $("#rs_category option:selected").attr('id'),
                price: $('#rs_price').val(),
                restoID: self.setID
              }
             if (result) {
                self.saveNewMenu(data);
             }
          }
      });
    },
    saveNewMenu(data){
      axios.post('/api/restaurant/menu', data)
        .then(res => {
          console.log(res.data)
        }).catch(err => {
        console.log(err)
      })
    },
    getSales(id){
       axios.get(`api/restaurant/sales/${id}`)
        .then(res => {
            this.daily_sales = res.data[0].daily_sales
            this.monthly_sales = res.data[0].monthly_sales
            // console.log(res.data[0])
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
  button#add_to_cart {
      margin-right: 4px;
  }
</style>
