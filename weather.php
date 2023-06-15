<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <?php
        include('partials/head.php');
    ?>
    <link rel="stylesheet" href="assets/css/weather.css"></link>
    <title>Previsão do tempo | Aqua Jornal</title>
</head>
<body>    
    <div class="rain front-row"></div>
    <div class="rain back-row"></div>
    <?php
        include('partials/preload.php');
        include('partials/header.php');
    ?>
    <main>
        <!-- ================ contact section start ================= -->
        <section class="py-5">
            <div class="container py-5">
                <div class="row px-3">
                    <div class="col-md-4 widget">
                        <div class="loader" id="loader"></div>
                        <label class="toggle" id="toggle">
                            <input id="temp-toggle" type="checkbox" checked>
                            <i><p>°F</p><p>°C</p></i>
                        </label>
                        <div class="temp" id="temp"></div>
                        <div class="city" id="city"></div>
                    </div>
                    <div class="col-md-8 widget-infos">
                        <div class="now-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <p id="wild" class="d-flex align-items-center"><i class="fas fa-wind"></i><i class="far fa-spinner fa-spin"></i></p>
                                </div>
                                <div class="col-md-6">
                                    <p id="humidity" class="d-flex align-items-center"><i class="fas fa-tint"></i><i class="far fa-spinner fa-spin"></i></p>
                                </div>
                                <div class="col-md-6">
                                    <p id="room-temperature" class="d-flex align-items-center"><i class="fas fa-walking"></i><i class="far fa-spinner fa-spin"></i></p>
                                </div>
                                <div class="col-md-6">
                                    <p id="uv-indice" class="d-flex align-items-center"><i class="fas fa-sun"></i><i class="far fa-spinner fa-spin"></i></p>
                                </div>
                                <div class="col-md-6">
                                    <p id="precip" class="d-flex align-items-center"><i class="fas fa-cloud"></i><i class="far fa-spinner fa-spin"></i></p>
                                </div>
                                <div class="col-md-6">
                                    <p id="pressure" class="d-flex align-items-center"><i class="fas fa-angle-double-down"></i><i class="far fa-spinner fa-spin"></i></p>
                                </div>
                            </div>
                        </div>
                        <div class="source">
                            <p id="last-update"></p>
                            <p class="small px-2">fonte de dados <a href="https://weather.com" target="_blank">weather.com</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

<script>
    const WEATHER_API_KEY = "d860d1c7ae8a4fda9bd222436231904";
    const widgetEl = document.getElementById("widget");
    const loaderEl = document.getElementById("loader");
    const tempEl = document.getElementById("temp");
    const cityEl = document.getElementById("city");
    const toggleEl = document.getElementById("toggle");
    const toggleInputEl = document.getElementById("temp-toggle");
    const flagEl = document.getElementById("flag");
    const updateEl = document.getElementById("last-update");
    const wildEl = document.getElementById("wild");
    const humidityEl = document.getElementById("humidity");
    const roomTemperatureEl = document.getElementById("room-temperature");
    const uvIndiceEl = document.getElementById("uv-indice");
    const precipEl = document.getElementById("precip");
    const pressureEl = document.getElementById("pressure");
    
    let tempC = -1;
    let tempF = -1;
    let wild = -1;
    let tempD = "<?php echo date('d/m/Y H:i:s', strtotime('-10 minutes'));?>";
    let city = "New York, NY";
    let update = tempD;
    let countryCode = '';

    let temp = -1;
    let time = 0;
    const minTime = 1;
    const maxTime = 900;

    // http://upshots.org/actionscript/jsas-understanding-easing
    function easeInQuad(t, b, c, d) {
        return c * (t /= d) * t + b;
    }

    function getDir(dir){
        if(dir == "E"){
            return "Leste";
        }else if(dir ==  "W"){
            return "Oeste";
        }else if(dir == "N"){
            return "Norte";
        }else if(dir == "S"){
            return "Sul";
        }else if(dir.indexOf("SE") != -1){
            return "Sudeste"
        }else if(dir.indexOf("SW") != -1){
            return "Sudoeste"
        }else if(dir.indexOf("NW") != -1){
            return "Nordeste"
        }else if(dir.indexOf("NE") != -1){
            return "Noroeste"
        }
    }

    function setData(newTemp, newCity, newUpdate, newWild, newWildDir, newHumidity, newRoomTemperature, newUvIndice, newPrecip, newPressure, newImage, condition) {
        temp = newTemp;
        city = newCity;

        update = newUpdate.split(' ');
        let tempHour = update[1]
        let tempDate = update[0].split('-')
        tempDate = tempDate[2]+'/'+tempDate[1]+'/'+tempDate[0];
        update = tempDate+' '+tempHour;

        tempEl.innerHTML = `<img src="https://${newImage}">${newTemp}°`;
        cityEl.innerHTML = `próximo de ${newCity}`;
        updateEl.innerHTML = `Última atualização: ${update}`;
        wildEl.innerHTML = `<i class="fas fa-wind mr-3"></i> ventos de ${newWild} km/h ao `+getDir(newWildDir);
        humidityEl.innerHTML = `<i class="fas fa-tint mr-3"></i> ${newHumidity}% de humidade no ar `;
        roomTemperatureEl.innerHTML = `<i class="fas fa-walking mr-3"></i> ${newRoomTemperature}° sensação térmica`;
        uvIndiceEl.innerHTML = `<i class="fas fa-sun mr-3"></i> índice UV  ${newUvIndice} de 10`;
        precipEl.innerHTML = `<i class="fas fa-cloud mr-3"></i> ${newPrecip}mm de probabilidade de chuva`; 
        pressureEl.innerHTML = `<i class="fas fa-angle-double-down mr-3"></i> ${newPressure} hectopascal de pressão`; 

        console.log(condition)
        if(condition == 'Night'){
            $('.widget-infos .now-info p').css({color: '#fff'})
            $('.source p').css({color: 'rgb(220, 220, 220, 69%)'})
            $('body').css({background: 'rgb(3, 0, 31)'})
        }else if(condition === 'Partly cloudy'){
            $('body').css({background: 'rgb(171, 171, 171)'})
            $('.source p').css({color: 'rgb(0, 0, 0, 69%)'})
        }else if(condition.indexOf('rain')){
            $('body').css({background: 'linear-gradient(to bottom, #202020, #111119)'})
            $('.source p').css({color: 'rgb(220, 220, 220, 69%)'})
            $('.now-info p').css({color: 'rgb(220, 220, 220, 69%)'})
            $('body').addClass('back-row-toggle splat-toggle')
            makeItRain()
        }
    }

    function setTemp(newTemp) {
        tempEl.innerHTML = `${newTemp}°`;
        cityEl.innerHTML = `${city}`;
        temp = newTemp;
    }

    function hideLoading() {
        loaderEl.style.display = 'none';
        toggleEl.style.display = 'inline-block';
    }

    function changeTemp(nextTemp) {
        const lower = Math.min(temp, nextTemp);
        const higher = Math.max(temp, nextTemp);
        const diff = higher - lower;
        const increase = nextTemp > temp;
        let currTemp = increase ? lower : higher;

        toggleInputEl.setAttribute("disabled", "true");
        for (let i = 0; i <= diff; i++) {
            (function(s) {
                const timer = setTimeout(function() {
                    setTemp(increase ? currTemp++ : currTemp--);
                    if (currTemp === nextTemp) {
                    toggleInputEl.removeAttribute("disabled");
                    }
                }, time);
            })(i);

            time = easeInQuad(i, minTime, maxTime, diff);
        }

        temp = currTemp;
    }


    async function getLocation() {
    return new Promise((resolve, reject) => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async (pos) => {
                const { latitude, longitude } = pos.coords;

                const url = `https://geocode.maps.co/reverse?lat=${latitude}&lon=${longitude}`;

                try {
                    const resp = await fetch(url);
                    const data = await resp.json();
                    if (data) {
                        const { address } = data;
                        const { city, village, state_district, state, country, country_code } = address;
                        let placeStr = "";
                        if (city || village || state_district) {
                        placeStr = `${city || village || state_district}, ${state || country}`;
                        } else if (state && country) {
                        placeStr = `${state}, ${country}`;
                        } else if (country) {
                        placeStr = country;
                        }
                        countryCode = country_code;
                        resolve(placeStr);
                    }
                } catch (err) {
                    console.error(err);
                    reject(err);
                }
            });
        } else {
            return;
            console.log("Geolocation is not supported by this browser.");
        }
    });
    }

    async function getWeatherData() {
        try {
        if (navigator.userAgent.match(/chrome|chromium|crios/i)) {
            city = await getLocation();
        }
        } catch(e) {
            console.error(e);
        }
    
    
        const url =
            `https://api.weatherapi.com/v1/current.json?key=${WEATHER_API_KEY}&q=${city}+day=14`;

        try {
            const resp = await fetch(url);
            const data = await resp.json();

            tempC = Math.round(data.current.temp_c);
            tempF = Math.round(data.current.temp_f);
            tempD = data.current.last_updated;
        
            hideLoading();
            setData(tempC, city, tempD, data.current.wind_kph, data.current.wind_dir, data.current.humidity, data.current.feelslike_c, data.current.uv, data.current.precip_mm, data.current.pressure_mb, data.current.condition.icon.replace('//',''), data.current.condition.text);
        } catch (err) {
            hideLoading();
            console.error(err);
        }
    }

    getWeatherData();

    toggleInputEl.addEventListener('change', function() {
    if (this.checked) {
        changeTemp(tempC);
    } else {
        changeTemp(tempF);
    }
    });
</script>
<?php
    include('partials/footer.php');
    include('partials/search.php');
    include('partials/scripts.php');
?>

</html>