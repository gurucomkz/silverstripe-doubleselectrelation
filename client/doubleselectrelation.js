jQuery.entwine('ss', function($) {

    $('.field-doubleselectrelation').entwine({
        onmatch: function(e) {
            var dsr = $(this),
                btnRemoveAll = dsr.find('.dsr-remove-all'),
                btnRemoveOne = dsr.find('.dsr-remove'),
                btnSelectAll = dsr.find('.dsr-chooseall'),
                btnSelectOne = dsr.find('.dsr-add'),
                chosen = dsr.find('.dsr-chosen'),
                available = dsr.find('.dsr-available'),
                searchMap = {},
                inputName = dsr.attr('data-fieldname');
            var selectSome = function(list){
                list.removeClass('first').removeClass('selected').each(function(){
                    var li = $(this), mapped = li.data('helper');
                    chosen.append(this);
                    mapped.mkinput();
                });
                if(!available.find('li').length) {
                    btnSelectAll.attr('disabled',true);
                }
                btnSelectOne.attr('disabled',true);
                btnRemoveAll.attr('disabled',false);
            }
            var deselectSome = function(list){
                list.removeClass('first').removeClass('selected').each(function(){
                    var li = $(this), mapped = li.data('helper');
                    available.append(this);
                    mapped.rminput();
                });
                if(!chosen.find('li').length) {
                    btnRemoveAll.attr('disabled',true);
                }
                btnRemoveOne.attr('disabled',true);
                btnSelectAll.attr('disabled',false);
            }
            btnRemoveAll.click(function(){
                deselectSome(chosen.find('li'));
            });
            btnSelectOne.click(function(){
                selectSome(available.find('li.selected'));
            });
            btnRemoveOne.click(function(){
                deselectSome(chosen.find('li.selected'));
            });
            btnSelectAll.click(function(){
                selectSome(available.find('li'));
            });
            dsr.find('ul li').click(function(e){
                var li = $(this), all = li.parent().find('li');

                if(e.shiftKey) {
                    var first = li.parent().find('.first');
                    if(!first.length) return;
                    first.siblings().removeClass('selected');
                    var between = (all.index(this) > all.index(first)) ? first.nextUntil(this) : first.prevUntil(this);
                    between.addClass('selected');
                    li.addClass('selected');
                } else {
                    li.toggleClass('selected');
                    if(!e.ctrlKey && !e.metaKey) {
                        li.siblings().filter('.selected').removeClass('selected');
                        all.filter('.first').removeClass('first');
                        if(li.hasClass('selected')) {
                            li.addClass('first');
                        }
                    }
                }
                btnSelectOne.attr('disabled',!available.find('li.selected').length);
                btnRemoveOne.attr('disabled',!chosen.find('li.selected').length);
            });

            dsr.find('ul li').each(function(){
                var li = $(this), val = li.attr('data-value');
                var helper = {
                    li: li,
                    vis: true,
                    val: val,
                    first: false,
                    selected: false,
                    chosen: li.parent().is(chosen),
                    text: li.text().toLowerCase(),
                    mkinput: function(){
                        this.input = $('<input type="hidden">').attr('name',inputName).addClass('dsr-input').val(this.val);
                        dsr.append(this.input);
                    },
                    rminput: function(){
                        this.input.remove();
                        this.input = null;
                    }
                };
                //find input
                if(helper.chosen) {
                    helper.input = dsr.find('input.dsr-input[value="' + helper.val + '"]');
                }
                li.data('helper', helper);
                searchMap[val] = helper;
            });

            dsr.find('.selector-filter').keyup(function(){
                var filter = $(this), find = filter.find('input').val().toLowerCase();
                filter.parent().find('ul li').each(function(){
                    var li = $(this), mapped = li.data('helper');

                    if(!find.length || mapped.text.match(find)) {
                        if(!mapped.vis) {
                            li.show();
                            mapped.vis = true;
                        }
                    } else {
                        if(mapped.vis) {
                            li.hide();
                            mapped.vis = false;
                        }
                    }
                })
                .filter(':hidden').removeClass('first').removeClass('selected');
            });
            btnSelectOne.attr('disabled',!available.find('li.selected').length);
            btnRemoveOne.attr('disabled',!chosen.find('li.selected').length);
            btnSelectAll.attr('disabled',!available.find('li').length);
            btnRemoveAll.attr('disabled',!chosen.find('li').length);
        }
    });

});
