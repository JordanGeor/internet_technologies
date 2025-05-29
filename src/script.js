// DARK MODE με cookie
document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("theme-toggle");
  
    // Έλεγχος cookie
    const getCookie = (name) => {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(";").shift();
    };
  
    const setCookie = (name, value, days = 365) => {
      const date = new Date();
      date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
      document.cookie = `${name}=${value}; expires=${date.toUTCString()}; path=/`;
    };
  
    if (getCookie("theme") === "dark") {
      document.body.classList.add("dark-mode");
    }
  
    toggle.addEventListener("click", () => {
      document.body.classList.toggle("dark-mode");
      setCookie("theme", document.body.classList.contains("dark-mode") ? "dark" : "light");
    });
  
    // Ακορντεόν λογική
    const headers = document.querySelectorAll(".accordion-header");
    headers.forEach((header) => {
      header.addEventListener("click", () => {
        document.querySelectorAll(".accordion-content").forEach((content) => {
          if (content.previousElementSibling !== header) {
            content.style.display = "none";
          }
        });
        const content = header.nextElementSibling;
        content.style.display = content.style.display === "block" ? "none" : "block";
      });
    });
  });
  