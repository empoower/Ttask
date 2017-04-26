$("#search-images").submit(function (e) {
    $(".mbr-gallery-filter").find('ul').empty();
    $("#active-gallery").find("div").remove(); // remove the main images
    $(".carousel-inner").find("div").remove(); // remove carousel images
    $(".mbr-gallery-row").height(30);


    var url = "/ajaxLoadContent";
    var data = $("#search-images").serialize();
    var data2 = "";

    $.ajax({
        type: "POST",
        url: url,
        data: data, // serializes the form's elements.
        beforeSend: function () {
            $("#active-gallery").empty();
            $('.mbr-gallery-notification').html('<div class="loading"><img src="assets/theme/img/loading.gif" alt="loading..." /></div>');
            if (!$("#tags").val()) {
                $('.mbr-gallery-notification').html('<p class="alert alert-warning">Please enter a keyword as search value.</p>');
                return false;
            }
        },
        success: function (data) {
            $.cookie("language", data.language);

            var items = data.items;
            var authors = data.authors;
            $("#active-gallery").empty();
            $(".mbr-gallery-notification").empty();
            $.each(items, function (key, value) {
                $("#active-gallery").append(value.imageDivS);
                $(".carousel-inner").append(value.imageDivB);
                $('#lb-gallery4-7').carousel();
            });


            $(".mbr-gallery-filter").find('ul').append("<li class=\"mbr-gallery-filter-all active\">All</li>");

            if(authors != undefined){
                $.each(authors, function (key, value) {
                    $(".mbr-gallery-filter").find('ul').append("<li class=\"filter-user\">" + value + "</li>");
                });
            }


            $("#active-gallery").find('.mbr-gallery-item').on("click", function (index) {
                $(".carousel-inner").find(".carousel-item").attr("class", "carousel-item");
                var num = this.id;
                var res = num.replace("pic_m_", "pic_b_");
                setTimeout(
                    function () {
                        $(".carousel-inner").find("#" + res).attr("class", "carousel-item active");
                    }, 100);
            });
        },

        complete: function () {
            $(".mbr-gallery-filter-all").trigger("click");
            setTimeout(
                function () {
                    $(".mbr-gallery-filter-all").trigger("click");
                }, 500);
            setTimeout(
                function () {
                    $(".mbr-gallery-filter-all").trigger("click");
                }, 1000);
        }
    });

    e.preventDefault();
});