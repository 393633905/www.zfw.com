@extends('admin.common.main')
@section('css')
    <link rel="stylesheet" type="text/css" href="/css/pagination.css"/>
@section('cnt')
    @include('admin.common.success')
    <nav class="breadcrumb">
        <i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span> 用户管理
        <span class="c-gray en">&gt;</span> 用户列表
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新">
            <i class="Hui-iconfont">&#xe68f;</i>
        </a>
    </nav>
    <div class="pd-20">
        <div class="text-c"> 日期范围：
            <input type="text" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'datemax\')||\'%y-%M-%d\'}'})" id="datemin" class="input-text Wdate" style="width:120px;"> -
            <input type="text" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'datemin\')}',maxDate:'%y-%M-%d'})" id="datemax" class="input-text Wdate" style="width:120px;">
            <input type="text" class="input-text" style="width:250px" placeholder="输入会员名称、电话、邮箱" id="" name="">
            <button type="submit" class="btn btn-success" id="" name=""><i class="icon-search"></i> 搜用户</button>

        </div>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="l">
            <a href="javascript:;" class="btn btn-danger radius deleteAll"><i class="icon-trash"></i> 批量删除</a>
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary radius"><i class="icon-plus"></i> 添加用户</a>
             <a href="{{ route('admin.user.restore') }}" class="btn btn-warning radius"><i
                         class="icon-plus"></i> 回收站</a>
        </span>
            <span class="r">共有数据：<strong>{{$users->total()}}</strong> 条</span>
        </div>

        <table class="table table-border table-bordered table-hover table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="25"><input type="checkbox" name="" value=""></th>
                <th width="40">ID</th>
                <th width="60">用户名</th>
                <th width="60">角色名</th>
                <th width="60">真实姓名</th>
                <th width="30">性别</th>
                <th width="80">手机</th>
                <th width="160">邮箱</th>
                <th width="120">加入时间</th>
                <th width="70">状态</th>
                <th width="150">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $v)
                <tr class="text-c">
                    @if($v->id == auth()->id())
                        <td></td>
                    @else
                        <td><input type="checkbox" value="{{$v->id}}" name="id[]"></td>
                    @endif
                    <td>{{$v->id}}</td>
                    <td><u style="cursor:pointer" class="text-primary">{{$v->username}}</u></td>

                        @if($v->role['name'])
                            <td>{{$v->role['name']}}</td>
                        @else
                            <td><font color="red">未分配</font></td>
                            @endif

                    <td>{{$v->truename}}</td>
                    <td>{{$v->gender}}</td>
                    <td>{{$v->mobile}}</td>
                    <td>{{$v->email}}</td>
                    <td>{{$v->created_at}}</td>
                    <td class="user-status"><span class="label label-success">已启用</span></td>
                    <td class="f-14 user-manage">
                        @if($v->id !=auth()->id())
                            {!! $v->btn('admin.user.edit','分配角色') !!}
                        @endif
                            {!! $v->btn('admin.user.edit','修改') !!}
                        @if($v->id !=auth()->id())
                                {!! $v->btn('admin.user.delete','删除') !!}
                        @endif

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div id="pageNav" class="pageNav">
            {{$users->render()}}
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            const _token = "{{csrf_token()}}";
            $('.del').click(function () {
                layer.confirm('您真的要删除当前用户吗？', {
                    btn: ['确认', '取消'] //按钮
                }, () => {//确认
                    let url = $(this).attr('href');
                    //发送ajax delete请求：
                    $.ajax({
                        url,
                        type: 'delete',
                        data: {_token},
                        dataType: 'json',
                    }).then((ret) => {
                        if (ret.code == 200) {  //删除成功
                            layer.msg('删除成功', {icon: 1});
                            $(this).parents('tr').remove();
                        }
                    });
                });
                //禁用a标签默认事件
                return false;
            });

            //删除全部用户：
            $('.deleteAll').click(function () {
                layer.confirm('您真的要删除指定的用户吗？', {
                    btn: ['确认', '取消'] //按钮
                }, () => {//确认
                    //获取所有已经选中的checkbox:
                    let ids = [];
                    $("input[name='id[]']:checked").each((i, v) => {
                        ids.push($(v).val());
                    });

                    $.ajax({
                        url: "{{route('admin.user.delall')}}",
                        type: 'delete',
                        data: {_token, id: ids},
                        dataType: 'json',
                    }).then((ret) => {
                        if (ret.code == 200) {  //删除成功
                            layer.msg('删除成功', {icon: 1});
                            $("input[name='id[]']:checked").parents('tr').remove();
                        }
                    });
                });
            });
        });
    </script>
@endsection