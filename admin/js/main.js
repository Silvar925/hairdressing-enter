let blocks = $(".admin_info__elem");
let menu = $(".admin_menu__items a");
let currentUrl = new URL(window.location.href);
let tabData = currentUrl.searchParams.get('tab_name');
let isOpened = localStorage.getItem('isOpened');

if (tabData == null) {
    tabData = menu.attr('data_info');
    let currentUrl = window.location.href;
    let newParam = 'tab_name=' + tabData;
    let updatedUrl = currentUrl + '?' + newParam;

    window.history.pushState({ path: updatedUrl }, '', updatedUrl);
    location.reload();
}

setTimeout(function () {
    $('.autentificationForm_block__message___success').remove();
}, 5000);

if (isOpened == 'add') {
    $(".admin_info__elem___formData").show();
    $(".admin_info__elem___allDataFromDB").hide();
    $('.openAll').removeClass('activeTabName');
    $('.openForm').addClass('activeTabName');
} else if (isOpened == 'all') {
    $(".admin_info__elem___formData").hide();
    $(".admin_info__elem___allDataFromDB").show();
    $('.openAll').addClass('activeTabName');
    $('.openForm').removeClass('activeTabName');
}

blocks.each(function () {
    if ($(this).attr("data_info") == tabData) {
        $(this).show();
    } else {
        $(this).hide();
    }
})

menu.each(function () {
    if ($(this).attr("data_info") == tabData) {
        $(".admin_menu__items a").removeClass("activeItem");
        $(this).addClass("activeItem");
        $(this).append(`<div class="triangle"></div>`);
    }
})


$(".admin_menu__items li").click(function () {
    console.log(111);
    $(".admin_menu__items li").removeClass("activeItem");
    $(this).addClass("activeItem");
    let tabName = $(this).attr("data_info");
    localStorage.setItem('tab_name', tabName);

    let currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('tab_name', tabName);
    window.history.replaceState({}, document.title, currentUrl);

    let blocks = $(".admin_info__elem");
    blocks.each(function () {
        if ($(this).attr("data_info") == tabName) {
            $(this).show();
            localStorage.setItem('isOpened', '');
            location.reload();
        } else {
            $(this).hide();
        }
    })
})

$(".openForm").click(function () {
    $(".admin_info__elem___formData").show();
    $(".admin_info__elem___allDataFromDB").hide();
    localStorage.setItem('isOpened', 'add');
})

$(".openAll").click(function () {
    $(".admin_info__elem___formData").hide();
    $(".admin_info__elem___allDataFromDB").show();
    localStorage.setItem('isOpened', 'all');
})

$('.admin_info__elem___allData').click(function () {
    $('.admin_info__elem___allData').removeClass('activeTabName');
    $(this).addClass('activeTabName');
})


$(`.${tabData}Delete`).on('click', function () {
    var id = $(this).attr('idtodel');
    var tableName = tabData;

    $.ajax({
        type: 'POST',
        url: 'includes/CRUD/deleteData.php',
        data: {
            id: id,
            tableName: tableName
        },
        success: function (response) {
            location.reload();
        },
        error: function (error) {
            console.log(error);
        }
    });
})
