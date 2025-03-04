<script src="{{ asset('backend/js/autocomplete.min.js') }}"></script>
<script>
    new Autocomplete("leaflet_search", {
        // default selects the first item in
        // the list of results
        selectFirst: true,

        // The number of characters entered should start searching
        howManyCharacters: 2,

        // onSearch
        onSearch: ({
            currentValue
        }) => {
            // You can also use static files
            // const api = '../static/search.json'
            const api =
                `https://nominatim.openstreetmap.org/search?format=geojson&limit=1&city=${encodeURI(currentValue)}`;

            return new Promise((resolve) => {
                fetch(api)
                    .then((response) => response.json())
                    .then((data) => {
                        resolve(data.features);
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });
        },
        // nominatim GeoJSON format parse this part turns json into the list of
        // records that appears when you type.
        onResults: ({
            currentValue,
            matches,
            template
        }) => {
            const regex = new RegExp(currentValue, "gi");

            // if the result returns 0 we
            // show the no results element
            return matches === 0
        ? template
        : matches
              .map((element) => {
                  let split_address = element.properties.display_name.split(', ');
                  if (split_address.length >= 3) {
                      let country = split_address[split_address.length - 1];
                      let city = split_address[0]; // First element
                      let data = city + ', ' + country;

                      // Highlight the search term in the modified text
                      let modifiedText = data.replace(
                          regex,
                          (str) => `<b>${str}</b>`
                      );

                      return `
                        <li class="loupe">
                            <p>
                                ${modifiedText}
                            </p>
                        </li>`;
                  } else {
                      console.log("Not enough elements in the array to extract.");
                  }
              })
              .join("");
},

        // we add an action to enter or click
        onSubmit: ({
            object
        }) => {
            // console.log(object)
            // remove all layers from the map
            leaflet_map.eachLayer(function(layer) {
                if (!!layer.toGeoJSON) {
                    leaflet_map.removeLayer(layer);
                }
            });

            const {
                display_name
            } = object.properties;
            const [lng, lat] = object.geometry.coordinates;

            //    const marker = L.marker([lat, lng], {
            //         title: display_name,
            //    });

            //    marker.addTo(leaflet_map).bindPopup(display_name);

            leaflet_map.setView([lat, lng], 8);
        },

        // get index and data from li element after
        // hovering over li with the mouse or using
        // arrow keys ↓ | ↑
        onSelectedItem: ({
            index,
            element,
            object
        }) => {
            //    console.log(object.properties)
            //    console.log(object.geometry.coordinates)
            let leaf_lon = object.geometry.coordinates[0]
            let leaf_lat = object.geometry.coordinates[1]
            let full_address = object.properties.display_name
            let split_address = full_address.split(', ');

            if (split_address.length >= 3) {
                let country = split_address[split_address.length - 1];
                let state = split_address[1]; // Second element
                let city = split_address[0]; // First element

                // Now you have the country, state, and city
                console.log("Country: " + country);
                console.log("State: " + state);
                console.log("City: " + city);

                // You can use this information as needed, for example, to update form fields or make API requests.
            } else {
                console.log("Not enough elements in the array to extract.");
            }

            $('.leaf_lon').val(leaf_lon);
            $('.leaf_lat').val(leaf_lat);

            let split_string = full_address.split(', ');
            let country = split_string.pop();

            // var form = new FormData();
            // form.append('lat', leaf_lat);
            // form.append('lng', leaf_lon);
            // form.append('country', country);
            // form.append('place', full_address);

            // axios.post('/set/session', form)
            // .then((res) => {
            //     // alert()
            //     // console.log(res.data);
            //     // toastr.success("Location Saved", 'Success!');
            // })
            // .catch((e) => {
            //     toastr.error("Something Wrong", 'Error!');
            // });
        },

        // the method presents no results element
        noResults: ({
                currentValue,
                template
            }) =>
            template(`<li>No results found: "${currentValue}"</li>`),
    });
</script>
