// Elements
const regionSelect = document.getElementById("region");
const provinceSelect = document.getElementById("province");
const citySelect = document.getElementById("city");
const barangaySelect = document.getElementById("barangay");

// Load JSON Data
const loadJson = async (url) => {
  try {
    const response = await fetch(url);
    return await response.json();
  } catch (error) {
    console.error("Error loading JSON:", error);
  }
};

// Initialize Regions, Provinces, Cities, and Barangays
(async () => {
  const regions = await loadJson("addressJSON/region.json");
  const provinces = await loadJson("addressJSON/province.json");
  const cities = await loadJson("addressJSON/city.json");
  const barangays = await loadJson("addressJSON/barangay.json");

  populateRegions(regions);

  // Region Selection
  regionSelect.addEventListener("change", () => {
    const selectedRegion = regionSelect.value;
    populateProvinces(provinces, selectedRegion);
    resetDropdown(citySelect);
    resetDropdown(barangaySelect);
  });

  // Province Selection
  provinceSelect.addEventListener("change", () => {
    const selectedProvince = provinceSelect.value;
    populateCities(cities, selectedProvince);
    resetDropdown(barangaySelect);
  });

  // City Selection
  citySelect.addEventListener("change", () => {
    const selectedCity = citySelect.value;
    populateBarangays(barangays, selectedCity);
  });
})();

// Reset Dropdown Function
function resetDropdown(dropdown) {
  dropdown.innerHTML = `<option value="">Select</option>`;
  dropdown.disabled = true;
}

// Populate Regions
function populateRegions(regions) {
  regions.forEach(({ region_code, region_name }) => {
    const option = document.createElement("option");
    option.value = region_code;
    option.textContent = region_name;
    regionSelect.appendChild(option);
  });
}

// Populate Provinces
function populateProvinces(provinces, regionCode) {
  resetDropdown(provinceSelect);
  if (regionCode) {
    provinceSelect.disabled = false;
    const filteredProvinces = provinces.filter(
      (province) => province.region_code === regionCode
    );
    filteredProvinces.forEach(({ province_code, province_name }) => {
      const option = document.createElement("option");
      option.value = province_code;
      option.textContent = province_name;
      provinceSelect.appendChild(option);
    });
  }
}

// Populate Cities
function populateCities(cities, provinceCode) {
  resetDropdown(citySelect);
  if (provinceCode) {
    citySelect.disabled = false;
    const filteredCities = cities.filter(
      (city) => city.province_code === provinceCode
    );
    if (filteredCities.length === 0) {
      console.warn(`No cities found for province_code: ${provinceCode}`);
    }
    filteredCities.forEach(({ city_code, city_name }) => {
      const option = document.createElement("option");
      option.value = city_code;
      option.textContent = city_name;
      citySelect.appendChild(option);
    });
  }
}

// Populate Barangays
function populateBarangays(barangays, cityCode) {
  resetDropdown(barangaySelect);

  if (cityCode) {
    barangaySelect.disabled = false;

    const filteredBarangays = barangays.filter(
      (barangay) => barangay.city_code === cityCode
    );

    if (filteredBarangays.length === 0) {
      console.warn(`No barangays found for city_code: ${cityCode}`);
      return;
    }

    filteredBarangays.forEach(({ brgy_code, brgy_name }) => {
      const option = document.createElement("option");
      option.value = brgy_code;
      option.textContent = brgy_name;
      barangaySelect.appendChild(option);
    });
  }
}
