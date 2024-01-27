document.addEventListener("DOMContentLoaded", () => {
   
  let text_color;
    let footer_color;
    Livewire.on('footer-text-color', (data) => {
        text_color = data.data;
        document.body.style.setProperty('--footer-text-color', text_color);
    })
    Livewire.on('footer-color', (data) => {
        footer_color = data.data;
        document.body.style.setProperty('--footer-color', footer_color);
    })
    const showAllPosts = document.querySelector("#all-posts")
    if(showAllPosts){
      showAllPosts.addEventListener("click", () => {
        location.href="#all-posts";
      })
    }
   
    

  const logoutButton = document.getElementById('logout-button')
  if (logoutButton) {
    new bootstrap.Tooltip(logoutButton); // Inisialisasi Logout Tooltip
  }

  let body = document.querySelector("body"),
    darkMode = body.querySelector('#dark-mode-switch')

  darkMode.addEventListener("click", () => {
    console.log('diklik');
    Livewire.dispatch('dark-mode');
    body.classList.toggle("dark")
  })
})
