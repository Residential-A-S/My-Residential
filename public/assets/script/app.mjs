import request from "/assets/script/request.mjs";

let forms = document.querySelectorAll("form:not([data-no-generic])");
forms.forEach((form) => {
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    let formData = new FormData(form);
    let data = {};
    formData.forEach((value, key) => {
      if (key in data) {
        if (!Array.isArray(data[key])) {
          data[key] = [data[key]];
        }
        data[key].push(value);
      } else {
        data[key] = value;
      }
    });
    request(data, (response) => {
      if (response.success) {
        alert(response.message);
      } else {
        alert(response.error);
      }
    });
  });
});

let dropdowns = document.querySelectorAll(".dropdown");

dropdowns.forEach((dropdown) => {
  let button = dropdown.querySelector(".dropdownHandle");
  let content = dropdown.querySelector(".dropdownCtn");
  button.addEventListener("click", () => {
    content.classList.toggle("active");
  });
});

document.body.addEventListener("click", (e) => {
  dropdowns.forEach((dropdown) => {
    if (!dropdown.contains(e.target)) {
      dropdown.querySelector(".dropdownCtn").classList.remove("active");
    }
  });
});

document.querySelectorAll(".callToAction").forEach((callToAction) => {
  callToAction.addEventListener("click", () => {
    let data = {
      action: callToAction.name,
      value: callToAction.value,
    };
    request(data, (response) => {
      if (response.success) {
        location.reload();
      } else {
        console.log(response.error);
      }
    });
  });
});
