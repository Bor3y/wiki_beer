var api_url = "http://localhost:8000/api/";

export default class RandomBeerService {
    static getRandomBeer() {
        return axios.get(api_url+"beers/random", {}).then(function (response) {
            return response.data;
        })
    }

    static getAllBeersOfBrewery(breweryId) {
        return axios.get(api_url+`brewery/${breweryId}/beers`, {}).then(function (response) {
            return response.data;
        })
    }

    static search(searchText, type) {
        return axios.get(api_url+`search?q=${searchText}&type=${type}`, {}).then(function (response) {
            return response.data;
        })
    }
}