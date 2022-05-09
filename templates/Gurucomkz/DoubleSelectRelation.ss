<div class="field-doubleselectrelation" data-fieldname="$Name[]" <% if $AllowPreview %>data-preview="$Link('preview')"<% end_if %>>

    <p class="help">
        <%t Gurucomkz\\DoubleSelectRelation.Instructions 'Hold down "Control", or "Command" on a Mac, to select more than one.' %>
    </p>

    <div class="selector">
        <div class="selector-available">
            <h2><%t Gurucomkz\\DoubleSelectRelation.Available 'Available' %></h2>
            <div class="selector-filter">
                <input type="text" class="text" placeholder="<%t Gurucomkz\\DoubleSelectRelation.FilterAvailable 'Filter Available' %>" autocomplete="off">
            </div>
            <ul class="dsr-available form-control">
                <% loop $Options %><% if not $isChecked %>
                <li data-value="$Value"><span>$Title</span></li>
                <% end_if %><% end_loop %>
            </ul>
            <div class="dsr-actions">
                <button type="button" title="<%t Gurucomkz\\DoubleSelectRelation.ChooseAllNote 'Click to choose all items at once' %>" class="btn btn-outline-primary font-icon-tick dsr-chooseall"><%t Gurucomkz\\DoubleSelectRelation.ChooseAll 'Choose all' %></button>
                <button type="button" title="<%t Gurucomkz\\DoubleSelectRelation.Choose 'Choose' %>" class="btn btn-primary font-icon-plus  dsr-add"><%t Gurucomkz\\DoubleSelectRelation.Choose 'Choose' %></button>
            </div>
        </div>
        <div class="selector-chosen">
            <h2><%t Gurucomkz\\DoubleSelectRelation.Chosen 'Chosen' %></h2>
            <div class="selector-filter">
                <input type="text" class="text" placeholder="<%t Gurucomkz\\DoubleSelectRelation.FilterChosen 'Filter Chosen' %>" autocomplete="off">
            </div>
            <ul class="dsr-chosen form-control">
                <% loop $Options %><% if $isChecked %>
                <li data-value="$Value"><span>$Title</span></li>
                <% end_if %><% end_loop %>
            </ul>
            <div class="dsr-actions">
                <button
                    type="button" 
                    title="<%t Gurucomkz\\DoubleSelectRelation.Remove 'Remove' %>" 
                    class="btn btn-danger font-icon-minus dsr-remove">
                    <%t Gurucomkz\\DoubleSelectRelation.Remove 'Remove' %>
                </button>
                <button 
                    type="button"
                    title="<%t Gurucomkz\\DoubleSelectRelation.RemoveAllNote 'Click to remove all chosen items at once' %>" 
                    class="btn btn-outline-danger font-icon-trash-bin dsr-remove-all">
                    <%t Gurucomkz\\DoubleSelectRelation.RemoveAll 'Remove all' %>
                </button>
            </div>
        </div>
    </div>
    <% loop $Options %><% if $isChecked %>
    <input type="hidden" class="dsr-input" name="$Up.Name[]" value="$Value">
    <% end_if %><% end_loop %>

    <div class="DSRPreviewModal modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><%t Gurucomkz\\DoubleSelectRelation.Preview 'Preview' %></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<%t Gurucomkz\\DoubleSelectRelation.Close 'Close' %>"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
</div>
