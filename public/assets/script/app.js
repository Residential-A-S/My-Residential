document.querySelectorAll("form").forEach((form) => {
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    let formData = new FormData(form);
    console.log(formData);
    request(formData, (response) => {
      if (response.success) {
        alert(response.message);
      } else {
        alert(response.error);
      }
    });
  });
});

function request(data, callback) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/xhr", true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onreadystatechange = () => {
    if (xhr.readyState === 4) {
      callback(JSON.parse(xhr.responseText));
    }
  };
  let requestData = {};
  if(data instanceof FormData) {
    data.forEach((value, key) => requestData[key] = value);
  }
  else if(Array.isArray(data)){
    data.forEach((item, index) => requestData[index] = item);
  }
  else{
    requestData = data;
  }
  xhr.send(JSON.stringify(requestData));
}