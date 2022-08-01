function initialSetup() {
  var result = "<option value='";
  for (var i = 0; i < states.length; i++) {
    result += states[i];
    result += "'></option><option value='";
  }
  result += "'></option>";

  document.getElementById("stateOptions").innerHTML = result;
}

function selectCities() {
  var result = "<option value='";
  var cityList =
    cities[states.indexOf(document.getElementById("inputState").value)];
  for (var i = 0; i < cityList.length; i++) {
    result += cityList[i];
    result += "'></option><option value='";
  }
  result += "'></option>";

  document.getElementById("cityOptions").innerHTML = result;
}

function showResult() {
  if (
    document.getElementById("inputName").value &&
    document.getElementById("inputAge").value &&
    document.getElementById("inputBloodGroup").value &&
    document.getElementById("inputNoOfUnits").value &&
    document.getElementById("inputState").value &&
    document.getElementById("inputCity").value &&
    document.getElementById("inputContactNo").value
  ) {
    var result = "Blood Needed for:<br>";
    result += document.getElementById("inputName").value;
    result += ", Age ";
    result += document.getElementById("inputAge").value;
    result += " from ";
    result += document.getElementById("inputCity").value;
    result += " District, ";
    result += document.getElementById("inputState").value;
    result += ".<br>Blood Group: ";
    result += document.getElementById("inputBloodGroup").value;
    result += ".<br>No. of units needed: ";
    result += document.getElementById("inputNoOfUnits").value;
    result += ".<br>Contact Number: ";
    result += document.getElementById("inputContactNo").value;
    document.getElementById("details").innerHTML = result;
    document.getElementById("result").style.display = "inline";
  }
}

function hideResult() {
  document.getElementById("result").style.display = "none";
}
