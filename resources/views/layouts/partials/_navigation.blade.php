<!-- Navigation -->
<section class="mbr-section mbr-section__container article" id="header3-g"
         style="background-color: rgb(255, 255, 255); padding-top: 20px; padding-bottom: 20px;">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h3 class="mbr-section-title display-2">Page for viewing photos from Flickr</h3>
                <small class="mbr-section-subtitle">Test Task Igor Kuzmin April 26, 2017</small>
            </div>
        </div>

        <form class="form-inline" id="search-images">

            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>

            <div class="form-group">
                <label for="language">Language:</label>
                <select class="form-control search-language" name="language" id="language">
                    @foreach(\App\Flickr::getLanguages() as $key=>$languageStr)
                        <option value="{{$key}}"
                                @if(isset($_COOKIE["language"]) && $_COOKIE["language"] == $key)  selected @endif >{{ $languageStr }}</option>
                    @endforeach
                </select>

            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label>Tags:</label>

                {!! Form::select('tags[]',[],null,['id'=>'tags', 'multiple'=>"multiple" ,'class' => 'js-example-basic-multiple search-tag']) !!}
                <script type="text/javascript">

                    $("#tags").select2({
                        tags: true,
                        tokenSeparators: [',', '.', ' '],
                        closeOnSelect: true,
                        selectOnBlur: true,
                        selectOnClose: true,
                        maximumInputLength: 30,
                        "language": {
                            "noResults": function () {
                                return "Please enter some tags";
                            }
                        },
                    });

                    //                    $("#tags").on("select2:close", function(e) {
                    //                        alert('ddd');
                    //                    });


                </script>

            </div>

            <div class="checkbox">
                <label><input type="checkbox" name="tagmode" id="tagmode"> Must have ALL the tags</label>
            </div>

            <div class="form-group search-button-group">
                <button class="btn btn-success load-image search-button" type="submit">Search Image</button>
            </div>
        </form>


    </div>
</section>

