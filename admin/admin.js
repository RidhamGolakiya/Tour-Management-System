$(document).ready(function () {
  let userProfile = localStorage.getItem("user");
  userProfile = userProfile && JSON.parse(userProfile);
  let setting = localStorage.getItem("settings");
  setting = setting && JSON.parse(setting);

  document.documentElement.style.setProperty("--primary", setting.themeColor);
  const profileImage = $("#profileImage");

  if (userProfile?.image) {
    const imageUrl = userProfile.image.startsWith("https://")
      ? userProfile.image
      : `../uploads/users/${userProfile.image}`;
    // Check if the image URL exists
    $.get(imageUrl)
      .done(function () {
        // Image exists, set the source
        profileImage.attr("src", imageUrl);
      })
      .fail(function () {
        // Image doesn't exist, show the default image
        profileImage.attr("src", `../assets/images/profile/user.jpg`);
      });
  } else {
    profileImage.attr("src", `../assets/images/profile/user.jpg`);
  }

  userProfile &&
    $("#profileImage").after(`<span>${userProfile?.name ? userProfile?.name : userProfile?.username}</span>`);

  var sidebarData = [
    {
      link: setting.appUrl+"/admin/dashboard.php",
      iconClass: "fa-solid fa-chart-pie",
      label: "Dashboard",
    },
    {
      link: setting.appUrl+"/admin/users.php",
      iconClass: "fa-solid fa-users",
      label: "Users",
    },
    {
      link: setting.appUrl+"/admin/managers.php",
      iconClass: "fa-solid fa-user",
      label: "Managers",
    },
    {
      link: setting.appUrl+"/admin/tour-packages.php",
      iconClass: "fa-solid fa-cubes",
      label: "Tour Packages",
      sublink:[
        "/admin/edit-package.php",
        "/admin/tour-package-details.php",
        "/admin/create-package.php"
      ]
    },
    {
      link: setting.appUrl+"/admin/booking.php",
      iconClass: "fa-solid fa-cubes",
      label: "Package Booking",
    },
    {
      link: setting.appUrl+"/admin/blogs.php",
      iconClass: "fa-solid fa-paste",
      label: "Blogs",
      sublink:[
        "/admin/edit-blog.php",
        "/admin/create-blog.php"
      ]
    },
    {
      link: setting.appUrl+"/admin/transactions.php",
      iconClass: "fa-solid fa-money-bill-wave",
      label: "Transactions",
    },
    {
      link: setting.appUrl+"/admin/enquiries.php",
      iconClass: "fa-solid fa-question-circle",
      label: "Enquiries",
    },
    {
      link: setting.appUrl+"/admin/security-questions.php",
      iconClass: "fa-solid fa-shield",
      label: "Security Questions",
    },
    {
      link: setting.appUrl+"/admin/countries.php",
      iconClass: "fa-solid fa-globe",
      label: "Countries",
    },
    {
      link: setting.appUrl+"/admin/states.php",
      iconClass: "fas fa-map-marker-alt",
      label: "States",
    },
    {
      link: setting.appUrl+"/admin/settings.php",
      iconClass: "fa-solid fa-gear",
      label: "Settings",
    },
  ];

  function generateSidebarItems() {
    var sidebarnav = $("#sideNav");
    var currentURL = window.location.href;

    for (var i = 0; i < sidebarData.length; i++) {
      var item = sidebarData[i];

      var li = $("<li></li>").addClass("text sidebar-item");
      var a = $("<a></a>")
        .addClass("sidebar-link")
        .attr("href", item.link)
        .attr("aria-expanded", "false")
        .addClass(
          currentURL.includes(item.link) ||
            (item.sublink && item.sublink.some(sublink => currentURL.includes(sublink)))
            ? "active"
            : ""
        );

      var iconSpan = $("<span></span>").append(
        $("<i></i>").addClass(item.iconClass)
      );
      var labelSpan = $("<span></span>").addClass("hide-menu").text(item.label);

      a.append(iconSpan, labelSpan);
      li.append(a);
      sidebarnav.append(li);
    }
  }

  generateSidebarItems();
});

$(document).ready(function () {
  $("#logoutButton").on("click", function () {
    Swal.fire({
      text: "Are you sure to logout?",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes,Logout !!",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../queries.php",
          method: "post",
          data: {
            logout: true,
          },
          success: function (result) {
            if (result === "success") {
              // Logout was successful
              localStorage.clear();
              toastr.success("Logout successfully!");
              setTimeout(function () {
                window.location.replace(
                  "/"
                );
              }, 1000); // Redirect after 1 second
            }
          },
          error: function () {
            // Handle error
            toastr.error("Logout failed. Please try again later.");
          },
        });
      }
    });
  });

  $("#changeInputType").on("click", function () {
    var passwordInput = $("#password");

    if (passwordInput.attr("type") === "password") {
      passwordInput.attr("type", "text");
      $("#eyeToggle").removeClass("fa-eye-slash").addClass("fa-eye");
    } else {
      passwordInput.attr("type", "password");
      $("#eyeToggle").removeClass("fa-eye").addClass("fa-eye-slash");
    }
  });
});

toastr.options = {
  positionClass: "toast-top-right",
  timeOut: 2000,
  progressBar: true,
};
