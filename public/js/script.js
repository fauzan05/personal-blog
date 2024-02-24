


document.addEventListener("DOMContentLoaded", () => {

  // button to up
  var buttonToTop = document.getElementById("buttonToTop");
  if (buttonToTop) {
    window.addEventListener("scroll", function () {
      var scrollPosition = window.scrollY;
      var halfViewportHeight = window.innerHeight / 10;

      if (scrollPosition > halfViewportHeight) {
        setTimeout(function () {
          buttonToTop.classList.add("show"); // Menambahkan kelas 'show' setelah jeda
        }, 200);
        buttonToTop.style.display = "flex";
        // buttonToTop.classList.add("show"); // Menambahkan kelas 'show' untuk menampilkan tombol
      } else {
        buttonToTop.style.display = "none";
        buttonToTop.classList.remove("show"); // Menghapus kelas 'show' untuk menyembunyikan tombol
      }
    });
    buttonToTop.addEventListener("click", function () {
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    });
  }





  
  const showAllPosts = document.querySelector("#all-posts")
  const showCommentsPost = document.querySelector('#showCommentsPost')
  if (showAllPosts) {
    showAllPosts.addEventListener("click", () => {
      location.href = "#all-posts";
    })
  }
  if (showCommentsPost) {
    showCommentsPost.addEventListener("click", () => {
      location.href = "#all-comments-post"
    });
  }



  const logoutButton = document.getElementById('logout-button')
  if (logoutButton) {
    new bootstrap.Tooltip(logoutButton); // Inisialisasi Logout Tooltip
  }


  let body = document.querySelector("body"),
    sun = body.querySelector('.sun')
  moon = body.querySelector('.moon')


  sun.addEventListener("click", () => {
    Livewire.dispatch('dark-mode');
    body.classList.toggle("dark")
  })

  moon.addEventListener("click", () => {
    Livewire.dispatch('dark-mode');
    body.classList.toggle("dark")
  })

  sun_sidebar = body.querySelector('.sun-sidebar'),
    moon_sidebar = body.querySelector('.moon-sidebar')
  if (sun_sidebar || moon_sidebar) {
    sun_sidebar.addEventListener("click", () => {
      body.classList.toggle("dark");
      Livewire.dispatch('dark-mode')
    })
    moon_sidebar.addEventListener("click", () => {
      body.classList.toggle("dark");
      Livewire.dispatch('dark-mode')
    })
  }


  const carausel = document.querySelector(".card-note-wrapper");

if (carausel) {
  let firstNote = carausel.querySelector(".card-note");
  let slideButton = document.querySelectorAll(".nav-note-button");
  let firstNoteWidth = firstNote.clientWidth; // nilai awal 335
  let scrollWidth = carausel.scrollWidth - firstNoteWidth;
  let state;
  firstNote.style.paddingLeft = slideButton[0].clientWidth + "px"
  // console.log(slideButton[0].clientWidth)
  slideButton[0].style.display = "none"; // button previous dimatikan pertama x
  const showHideIcons = () => {
    slideButton[0].style.display = state <= 0 ? "none" : "flex";
    carausel.style.marginLeft = state <= 0 ? `46px` : '0px';
    slideButton[1].style.display = state >= scrollWidth ? "none" : "flex";
    carausel.style.marginRight = state >= scrollWidth ? `46px` : '0px';
  };

  const refreshCarousel = () => {
    firstNote = carausel.querySelector(".card-note");
    firstNoteWidth = firstNote.clientWidth;
    scrollWidth = carausel.scrollWidth - firstNoteWidth;
    showHideIcons();
  };

  slideButton.forEach(icon => {
    icon.addEventListener("click", () => {
      firstNote.style.paddingLeft = "0";
      if (icon.id == "right-button") {
        state = carausel.scrollLeft + firstNoteWidth;
        carausel.scrollLeft = state;
        refreshCarousel();
      }
      if (icon.id == "left-button") {
        state = carausel.scrollLeft - firstNoteWidth;
        carausel.scrollLeft = state;
        refreshCarousel();
      }
    });
  });
}

})
