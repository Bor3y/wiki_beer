<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">Random Beer</div>

                    <div class="panel-body">
                        <div class="col-lg-3" align="center">
                            <label>{{data.randomBeer.name}}</label>
                            <img width="150px" height="150px" :src="data.randomBeer.label"/>
                        </div>
                        <div class="col-lg-6">
                            <p>{{data.randomBeer.description}}</p>
                        </div>
                        <div class="col-lg-3">
                            <button style="padding: 10px; margin: 10px" @click="getRandomBeer()"
                                    class="col-lg-12 btn btn-primary">Another Beer
                            </button>
                            <button style="padding: 10px; margin: 10px" @click="getMoreFromBrewery()"
                                    class="col-lg-12 btn btn-primary">More From This Brewery
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">Search</div>

                    <div class="panel-body">
                        <form @submit.prevent="search">
                            <fieldset class="col-md-3" id="search">
                                <input class="col-md-12" v-model="searchForm.searchText" type="text" name="search" pattern="^([A-Za-z0-9]|\s|-)+$"
                                       required>
                                <div class="col-md-12" v-if="'q' in searchForm.errors"
                                     v-for="error in searchForm.errors.q">
                                    <span class="error">{{error}}</span>
                                </div>
                            </fieldset>


                            <fieldset class="col-md-6" id="type">
                                <div class="col-md-6" align="center">
                                    <input type="radio" v-model="searchForm.type" value="beer" name="type"
                                           required><label>Beer</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" v-model="searchForm.type" value="brewery" name="type"
                                           required><label>Brewery</label>
                                </div>
                                <div class="col-md-12" v-if="'type' in searchForm.errors"
                                     v-for="error in searchForm.errors.type">
                                    <span class="error">{{error}}</span>
                                </div>
                            </fieldset>

                            <button class="col-md-2 btn btn-primary" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <div class="row" id="searchResult" style="display: none">
            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">Search Results</div>

                    <div class="panel-body">
                        <ul id="moreBeer" style="list-style-type: none; display: none">
                            <li class="col-lg-12" v-for="beer in data.moreBeers" style="margin: 10px">
                                <img class="col-lg-2" :src="beer.label"/>
                                <div class="col-lg-10">
                                    <label class="col-lg-12">{{beer.name}}</label>
                                    <p class="col-lg-12">{{beer.description}}</p>
                                </div>
                            </li>
                        </ul>
                        <ul id="beers" style="list-style-type: none; display: none ">
                            <li class="col-lg-12" v-if="data.beers.length == 0" style="margin: 10px">
                                No Beer Match your Search
                            </li>
                            <li class="col-lg-12" v-for="beer in data.beers" style="margin: 10px">
                                <img class="col-lg-2" :src="beer.label"/>
                                <div class="col-lg-10">
                                    <label class="col-lg-12">{{beer.name}}</label>
                                    <p class="col-lg-12">{{beer.description}}</p>
                                </div>
                            </li>
                        </ul>
                        <ul id="breweries" style="list-style-type: none; display: none">
                            <li class="col-lg-12" v-if="data.breweries.length == 0" style="margin: 10px">
                                No brewery Match your Search
                            </li>
                            <li class="col-lg-12" v-for="brewery in data.breweries" style="margin: 10px">
                                <img class="col-lg-2" :src="brewery.image"/>
                                <div class="col-lg-10">
                                    <label class="col-lg-12">{{brewery.name}}</label>
                                    <p class="col-lg-12">{{brewery.description}}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <BlockUI v-if="loading" message="Loading..."></BlockUI>
    </div>
</template>

<script>
    import RandomBeerService from '../services/RandomBeerService';
    export default {
        data()
        {
            return {
                data: {
                    randomBeer: {
                        name: "",
                        label: "",
                        description: ""
                    },
                    moreBeers: [],
                    beers: [],
                    breweries: []
                },
                searchForm: {
                    searchText: null,
                    type: null,
                    errors: {}
                },
                loading: false
            }
        },
        methods: {
            getRandomBeer()
            {
                this.blockUI();
                RandomBeerService.getRandomBeer()
                    .then((data) => {this.data.randomBeer = data; this.unBlockUI(); })
                    .catch((err) => {console.log(err); this.unBlockUI();alert("opsss something wring");});
            },
            getMoreFromBrewery()
            {
                this.blockUI();
                RandomBeerService.getAllBeersOfBrewery(this.data.randomBeer.breweriesIds[0])
                    .then((data) => {
                            this.unBlockUI();
                            this.data.moreBeers = data;
                            $('#beers').css("display", "none");
                            $('#breweries').css("display", "none");
                            $('#moreBeer').css("display", "block");
                            $('#searchResult').css("display", "block");
                        },
                        (err) => {
                            this.unBlockUI();
                            console.log(err);
                            alert("opsss something wring");
                        });
            },
            search()
            {
                this.blockUI();
                this.searchForm.errors = {};
                RandomBeerService.search(this.searchForm.searchText, this.searchForm.type)
                    .then(
                        (data) => {
                            this.unBlockUI();
                            if (this.searchForm.type == "beer") {
                                this.data.beers = data;
                                $('#moreBeer').css("display", "none");
                                $('#breweries').css("display", "none");
                                $('#beers').css("display", "block");
                                $('#searchResult').css("display", "block");
                            }
                            else if (this.searchForm.type == "brewery") {
                                this.data.breweries = data;
                                $('#beers').css("display", "none");
                                $('#moreBeer').css("display", "none");
                                $('#breweries').css("display", "block");
                                $('#searchResult').css("display", "block");
                            }
                        })
                    .catch(
                        (error) => {
                            this.unBlockUI();
                            if(error.response.status == 422){
                                this.searchForm.errors = error.response.data.errors;
                            }else{
                                alert("opsss something wring");
                            }
                        }
                    );
            },
            blockUI()
            {
                this.loading = true;
            },
            unBlockUI(){
                this.loading = false;
            }
        },
        created() {
            this.getRandomBeer();
        }
    }
</script>
