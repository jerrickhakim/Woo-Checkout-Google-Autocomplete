const center = { lat: 50.064192, lng: -130.605469 };
// Create a bounding box with sides ~10km away from the center point
const defaultBounds = {
  north: center.lat + 0.1,
  south: center.lat - 0.1,
  east: center.lng + 0.1,
  west: center.lng - 0.1,
};
const input = document.getElementById("billing_address_1");
const options = {
  bounds: defaultBounds,
  //   componentRestrictions: {},
  fields: ["address_components"],
  strictBounds: false,
  types: ["address"],
};
const autocomplete = new google.maps.places.Autocomplete(input, options);

autocomplete.addListener("place_changed", fillInAddress);

function fillInAddress() {
  // Get the place details from the autocomplete object.
  const place = autocomplete.getPlace();
  //   console.log(place);
  let address1 = "";
  let postcode = "";

  // Get each component of the address from the place details,
  // and then fill-in the corresponding field on the form.
  // place.address_components are google.maps.GeocoderAddressComponent objects
  // which are documented at http://goo.gle/3l5i5Mr
  for (const component of place.address_components) {
    // @ts-ignore remove once typings fixed
    const componentType = component.types[0];

    switch (componentType) {
      case "street_number": {
        // console.log(component);
        address1 = `${component.long_name} ${address1}`;

        break;
      }

      case "route": {
        address1 += component.short_name;
        document.getElementById("billing_address_1").value = address1;
        break;
      }

      case "postal_code": {
        postcode = `${component.long_name}${postcode}`;

        document.querySelector("#billing_postcode").value = postcode;

        break;
      }

      case "postal_code_suffix": {
        postcode = `${postcode}-${component.long_name}`;
        break;
      }
      case "locality":
        document.querySelector("#billing_city").value = component.long_name;
        break;
      case "administrative_area_level_1": {
        document.querySelector("#billing_state").value = component.short_name;
        break;
      }
      case "country":
        document.querySelector("#billing_country").value = component.short_name;
        if (document.querySelector("#select2-billing_country-container")) {
          document.querySelector(
            "#select2-billing_country-container"
          ).innerHTML = component.long_name;
        }

        break;
    }
  }
}

const input2 = document.getElementById("shipping_address_1");
const options2 = {
  bounds: defaultBounds,
  //   componentRestrictions: {},
  fields: ["address_components"],
  strictBounds: false,
  types: ["address"],
};
const autocomplete2 = new google.maps.places.Autocomplete(input2, options2);

autocomplete2.addListener("place_changed", fillInAddress2);

function fillInAddress2() {
  // Get the place details from the autocomplete object.
  const place = autocomplete2.getPlace();
  //   console.log(place);
  let address1 = "";
  let postcode = "";

  // Get each component of the address from the place details,
  // and then fill-in the corresponding field on the form.
  // place.address_components are google.maps.GeocoderAddressComponent objects
  // which are documented at http://goo.gle/3l5i5Mr
  for (const component of place.address_components) {
    // @ts-ignore remove once typings fixed
    const componentType = component.types[0];

    switch (componentType) {
      case "street_number": {
        address1 = `${component.long_name} ${address1}`;
        break;
      }

      case "route": {
        address1 += component.short_name;
        document.getElementById("shipping_address_1").value = address1;

        break;
      }

      case "postal_code": {
        postcode = `${component.long_name}${postcode}`;

        document.getElementById("shipping_postcode").value = postcode;

        break;
      }

      case "postal_code_suffix": {
        postcode = `${postcode}-${component.long_name}`;
        break;
      }
      case "locality":
        document.querySelector("#shipping_city").value = component.long_name;
        break;
      case "administrative_area_level_1": {
        document.querySelector("#shipping_state").value = component.short_name;
        break;
      }
      case "country":
        document.querySelector("#shipping_country").value =
          component.short_name;

        if (document.querySelector("#select2-shipping_country-container")) {
          document.querySelector(
            "#select2-shipping_country-container"
          ).innerHTML = component.long_name;
        }

        break;
    }
  }
}
