<template>
    <div class="container pt-100">
         <div class="row mt-4">
            <div class="col-md-4">
                <div class="panel-subhead mt-4">
                    <div class="icon-subhead">
                        <img src="/img/icon/ic_user.png">
                    </div>
                    <div class="title-subhead">
                        <label>Settings</label>
                    </div>
                </div>
                 <div class="card p-0 pt-1 mt-4">
                    <nav-setting type="setting"></nav-setting>
                 </div>
            </div>
            <div class="col-md-8">
                <div class="panel-subhead pt-5 mt-5">
                    <input type="search" class="form-control w-100">
                </div>
                <div class="card mt-4 p-4 qna-wording">
                    <table class="w-100">
                        <tbody>
                            <tr data-toggle="collapse" data-target="#qna1">
                                <td> <h4>Linov Community</h4></td>
                                <td width="10" style="vertical-align: center;"><i class="fas fa-chevron-right"></i></td>
                            </tr>
                            <tr id="qna1" class="collapse in p3">
                                <td colspan="2">
                                    <p>Apa itu Linov Community?</p>
                                    <p>Bagaimana cara kerja Linov Community?</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</template>