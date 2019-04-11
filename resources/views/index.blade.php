<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$vars['id']}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        <div class="form-inline" id="{{$vars['id']}}">

        </div>
        <input type="hidden" name="{{$name}}" id="{{$column}}" value="{{ old($column, $value) }}">
        @include('admin::form.help-block')
    </div>
</div>

<script>
    (function(){
        var addSelect = function(parent_id){
            $.get("{{$vars['url']}}", {q: parent_id}, function(data){
                if(data.children.length){
                    var select = $("<select></select>");
                    select.addClass('form-control');
                    select.append('<option selected value="0">please select..</option>');

                    $.each(data.children, function(i,v){
                        select.append(`<option value="${v.id}">${v.title}</option>`);
                    });
                    $("#{{$vars['id']}}").append(select);
                    select.change(function(){
                        var that = $(this);
                        that.nextAll().remove();
                        $("{{$column}}").val(that.val());
                        if( that.val() ){
                            addSelect(that.val());
                        }
                    });
                }
            });
        };
        var initSelect = function(id){
            $.get("{{$vars['url']}}", {q: parent_id}, function(data){
                if(data.siblings.length){
                    var select = $("<select></select>");
                    select.addClass('form-control');
                    select.append('<option selected value="0">please select..</option>');

                    $.each(data.siblings, function(i,v){
                        select.append(`<option value="${v.id}" selected="${v.id - 0 == id - 0 ? 'true': 'false'}">${v.title}</option>`);
                    });
                    $("#{{$vars['id']}}").prepend(select);
                    select.change(function(){
                        var that = $(this);
                        that.nextAll().remove();
                        $("{{$column}}").val(that.val());
                        if( that.val() ){
                            addSelect(that.val());
                        }
                    });
                    if(data.own.parent_id - 0 != "{{$vars['top_id']}}" - 0) {
                        initSelect(data.own.parent_id);
                    }
                }
            });
        };
        if($("#{{$column}}").val()){
            initSelect($("#{{$column}}").val());
        }else{
            addSelect({{$vars['top_id']}});
        }
    }())
</script>

