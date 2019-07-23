<?php require 'pro_header.php'; ?>




<div class="main-container" id="main-container" style="opacity:0">

    <div class="left">
        <a-spin :spinning="spinning.left">
            <div class="main-header">
                <h3>Grades<a-tag color="green" style="transform: translateY(-3.1px);margin-left: 5px;">Beta</a-tag>
                </h3>
                <p>Manage/View your grades</p>
            </div>
            <template v-if="Object.keys(user.joined_classes).length">
                <div class="mes-item">
                    <p>
                        <a-icon type="team"></a-icon>&nbsp;&nbsp;Classes
                        <a-button size="small" @click="reverse_order('classes')" style="font-size:14px;">
                            <a-icon type="sort-descending" />
                        </a-button>
                    </p>
                </div>
                <div v-for="(joined,index) in user.joined_classes" :class="'class-item ' + class_super(index)" @click="open_class_info(index)" :id="'class'+index">
                    <div style="margin-right: 10px;">
                        <template v-if="!!user.classes_info[index].img">
                            <img :src="user.classes_info[index].img" class="class-item-img" />
                        </template>
                        <template v-else>
                            <div class="class-img-default">
                                <p>{{ user.classes_info[index].name.substring(0,1) }}</p>
                            </div>
                        </template>
                    </div>
                    <div>
                        <h3 v-html="user.classes_info[index].name+'<em>'+user.classes_info[index].member.split(',').length+'</em>'"></h3>
                        <p v-html="user.classes_info[index].des"></p>
                    </div>
                </div>
            </template>
        </a-spin>
    </div>

    <!-- 增加系列 -->
    <a-modal title="Add a new series" :visible="add.visible.series" @ok="handle_series_submit(opened_class_info.id)" :confirm-loading="add.confirm.series" @cancel="handle_series_cancel()">
        <a-input placeholder="Series Name" v-model="add.info.series.name">
            <a-icon slot="prefix" type="bar-chart" />
        </a-input>
    </a-modal>
    <!-- 增加系列结束 -->
    <!-- 增加主题 -->
    <a-modal title="Add a new topic" :visible="add.visible.topic" @ok="handle_topic_submit()" :confirm-loading="add.confirm.topic" @cancel="handle_topic_cancel()">
        <a-input placeholder="Topic Name" v-model="add.info.topic.name">
            <a-icon slot="prefix" type="bar-chart" />
        </a-input>
    </a-modal>
    <!-- 增加主题结束 -->
    <!-- 编辑主题 -->
    <a-modal title="Edit a topic" :visible="edit_info.visible.topic" @ok="handle_edit_info_topic_submit()" :confirm-loading="edit_info.confirm.topic" @cancel="handle_edit_info_topic_cancel()">
        <a-input placeholder="Topic Name" v-model="edit_info.info.topic.name">
            <a-icon slot="prefix" type="bar-chart" />
        </a-input>
    </a-modal>
    <!-- 编辑主题结束 -->
    <!-- 编辑系列 -->
    <a-modal title="Edit a series" :visible="edit_info.visible.series" @ok="handle_edit_info_series_submit()" :confirm-loading="edit_info.confirm.series" @cancel="handle_edit_info_series_cancel()">
        <a-input placeholder="Series Name" v-model="edit_info.info.series.name">
            <a-icon slot="prefix" type="bar-chart" />
        </a-input>
    </a-modal>
    <!-- 编辑系列结束 -->
    <!-- 查看 series 折线图 -->
    <a-modal :footer="null" title="View Series Stats" :visible="stats.visible.all" @cancel="handle_stats_cancel()">
        <template v-if="parseInt(user.id) == parseInt(opened_class_info.superid)">
            <div class="select-stats" v-for="(series_c,index) in opened_series_info.info" @click="open_stats('single',index,'view_topics')">
                <h2>{{ series_c.name }}</h2>
                <p>{{ (series_c.topics_info).length }} Topics</p>
            </div>
        </template>
        <template v-else>
            <div class="select-stats" v-for="(series_c,index) in opened_series_info.info" @click="open_stats('single',index)">
                <h2>{{ series_c.name }}</h2>
                <p>{{ (series_c.topics_info).length }} Topics</p>
            </div>
        </template>
        <p class="mes-end" style="margin-top: 0px;margin-bottom: 5px;">- EOF -</p>
    </a-modal>
    <!-- 查看 series 折线图结束 -->

    <div class="center">
        <a-spin :spinning="spinning.center">
            <template v-if="opened_series_info.status">
                <div :style="opened_series_info.info.length ? 'background: #f8f9fa;' : ''">
                    <div class="mes-header">
                        <p style="color:#666;">
                            <a-icon type="bar-chart"></a-icon>&nbsp;&nbsp;Series
                            <template v-if="parseInt(user.id) == parseInt(opened_class_info.superid)">
                                <a-button size="small" @click="open_stats('all')" style="right:81px;position:absolute">
                                    <a-icon type="line-chart"></a-icon>
                                </a-button>
                            </template>
                            <template v-else>
                                <a-button size="small" @click="open_stats('all')" style="right:20px;position:absolute">
                                    <a-icon type="line-chart"></a-icon>
                                </a-button>
                            </template>
                            <a-button size="small" @click="add.visible.series = true" style="right:20px;position:absolute" v-if="parseInt(user.id) == parseInt(opened_class_info.superid)">+ Add</a-button>
                        </p>
                    </div>

                    <template v-if="opened_series_info.info.length">

                        <template v-for="(series_c,index) in opened_series_info.info">
                            <div class="class-item series-item" :id="'series_sub'+series_c.id">
                                <h3>
                                    <a-icon type="fork"></a-icon>&nbsp;&nbsp;{{ series_c.name }}
                                </h3>
                                <template v-if="parseInt(user.id) == parseInt(opened_class_info.superid)">
                                    <a-button size="small" type="default" @click="open_edit_info_series(series_c.id,series_c.name)" style="right:119px;position:absolute;margin-top:1px">
                                        <a-icon type="edit"></a-icon>
                                    </a-button>
                                    <a-button size="small" type="default" @click="delete_series(series_c.id)" style="right:83px;position:absolute;margin-top:1px">
                                        <a-icon style="color:#FF4040" type="delete"></a-icon>
                                    </a-button>
                                    <a-button size="small" type="primary" @click="open_topic_submit(series_c.id,series_c.name)" style="right:20px;position:absolute;margin-top:1px">+ New</a-button>
                                </template>
                            </div>

                            <template v-if="series_c.topics_info.length">
                                <div v-for="topic_c in series_c.topics_info" class="class-item topic-item" :id="'topic_sub'+topic_c.id" @click="open_topic_info(topic_c.id,index)">
                                    <h3>
                                        <a-icon type="branches"></a-icon>&nbsp;&nbsp;{{ topic_c.name }}
                                        <p>{{ get_date(topic_c.date) }} | {{ topic_c.candidate_count }} Candidates</p>
                                    </h3>
                                </div>
                            </template>
                            <template v-else>
                                <div class="class-item topic-item">
                                    <h3>
                                        <a-icon type="branches"></a-icon>&nbsp;&nbsp;No Topics Yet
                                    </h3>
                                </div>
                            </template>

                        </template>

                    </template>
                    <template v-else>
                        <p class="mes-end">- EOF -</p>
                    </template>
                </div>
            </template>
        </a-spin>
        <!-- 占位 -->
        <template v-if="!spinning.center && !opened_series_info.status">
            <div style="padding:20px 30px">
                <a-skeleton :paragraph="{rows: 2}" v-for="i in 6"></a-skeleton>
            </div>
        </template>
        <!-- 占位 -->
    </div>


    <!-- 增加记录 -->
    <a-modal title="Add a Record" :visible="add.visible.record" @ok="handle_record_submit()" :confirm-loading="add.confirm.record" @cancel="handle_record_cancel()">
        <a-auto-complete class="certain-category-search" placeholder="Name" v-model="add.info.record.name" @change="change_name">
            <template slot="dataSource">
                <a-select-opt-group>
                    <a-select-option v-for="(person,index) in opened_topic_info.members_unused" :value="person.name + '|' + person.id + '|' + index">{{ person.name }}</a-select-option>
                </a-select-opt-group>
            </template>
        </a-auto-complete>
        <br /><br />
        <a-input-group>
            <a-input-number placeholder="Year" :min="2018" :max="2021" v-model="add.info.record.yy" style="margin-right:10px"></a-input-number>
            <a-input-number placeholder="Month" :min="1" :max="12" v-model="add.info.record.mm" style="margin-right:10px"></a-input-number>
            <a-input-number placeholder="Day" :min="1" :max="31" v-model="add.info.record.dd"></a-input-number>
        </a-input-group>
        <br />
        <a-input-group compact>
            <a-input-number placeholder="Total" style="width: 30%" :min="0" :step="0.5" v-model="add.info.record.total"></a-input-number>
            <a-input-number placeholder="Score" style="width: 30%" :min="0" :max="add.info.record.total" :step="0.5" v-model="add.info.record.score"></a-input-number>
        </a-input-group>
    </a-modal>
    <!-- 增加记录结束 -->

    <!-- 编辑记录 -->
    <a-modal title="Edit a Record" :visible="edit.visible" @ok="handle_edit_submit()" :confirm-loading="edit.confirm" @cancel="handle_edit_cancel()">
        <a-input-group>
            <a-input-number placeholder="Year" :min="2018" :max="2021" v-model="edit.yy" style="margin-right:10px"></a-input-number>
            <a-input-number placeholder="Month" :min="1" :max="12" v-model="edit.mm" style="margin-right:10px"></a-input-number>
            <a-input-number placeholder="Day" :min="1" :max="31" v-model="edit.dd"></a-input-number>
        </a-input-group>
        <br />
        <a-input-group compact>
            <a-input-number placeholder="Total" style="width: 30%" :min="0" :step="0.5" v-model="edit.total"></a-input-number>
            <a-input-number placeholder="Score" style="width: 30%" :min="0" :max="edit.total" :step="0.5" v-model="edit.score"></a-input-number>
        </a-input-group>
    </a-modal>
    <!-- 编辑记录结束 -->

    <!-- 设置范围 -->
    <a-modal title="Edit Grading Scale" :visible="range.visible" @ok="handle_range_submit()" :confirm-loading="range.confirm" @cancel="handle_range_cancel()">
        <a-form-item label="A*(max|min)">
            <a-input-group compact>
                <a-input-number style="width: 30%" :min="range.scale[0].min" :max="100" :step="1" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[0].max"></a-input-number>
                <a-input-number style="width: 30%" :min="0" :max="100" :disabled="true" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[0].min"></a-input-number>
            </a-input-group>
        </a-form-item>
        <a-form-item label="A(max|min)">
            <a-input-group compact>
                <a-input-number style="width: 30%" :min="range.scale[1].min" :max="100" :step="1" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[1].max" @change="change_a"></a-input-number>
                <a-input-number style="width: 30%" :min="0" :max="100" :disabled="true" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[1].min"></a-input-number>
            </a-input-group>
        </a-form-item>
        <a-form-item label="B(max|min)">
            <a-input-group compact>
                <a-input-number style="width: 30%" :min="range.scale[2].min" :max="100" :step="1" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[2].max" @change="change_b"></a-input-number>
                <a-input-number style="width: 30%" :min="0" :max="100" :disabled="true" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[2].min"></a-input-number>
            </a-input-group>
        </a-form-item>
        <a-form-item label="C(max|min)">
            <a-input-group compact>
                <a-input-number style="width: 30%" :min="range.scale[3].min" :max="100" :step="1" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[3].max" @change="change_c"></a-input-number>
                <a-input-number style="width: 30%" :min="0" :max="100" :disabled="true" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[3].min"></a-input-number>
            </a-input-group>
        </a-form-item>
        <a-form-item label="D(max|min)">
            <a-input-group compact>
                <a-input-number style="width: 30%" :min="range.scale[4].min" :max="100" :step="1" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[4].max" @change="change_d"></a-input-number>
                <a-input-number style="width: 30%" :min="0" :max="100" :disabled="true" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[4].min"></a-input-number>
            </a-input-group>
        </a-form-item>
        <a-form-item label="E(max|min)">
            <a-input-group compact>
                <a-input-number style="width: 30%" :min="range.scale[5].min" :max="100" :step="1" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[5].max" @change="change_e"></a-input-number>
                <a-input-number style="width: 30%" :min="0" :max="100" :disabled="true" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[5].min"></a-input-number>
            </a-input-group>
        </a-form-item>
        <a-form-item label="F(max|min)">
            <a-input-group compact>
                <a-input-number style="width: 30%" :min="range.scale[6].min" :max="100" :step="1" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[6].max" @change="change_f"></a-input-number>
                <a-input-number style="width: 30%" :min="0" :max="100" :disabled="true" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[6].min"></a-input-number>
            </a-input-group>
        </a-form-item>
        <a-form-item label="U(max|min)">
            <a-input-group compact>
                <a-input-number style="width: 30%" :min="0" :max="100" :step="0.1" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[7].max" @change="change_u"></a-input-number>
                <a-input-number style="width: 30%" :min="0" :max="100" :step="0.1" :formatter="value => `${value}%`" :parser="value => value.replace('%', '')" v-model="range.scale[7].min"></a-input-number>
            </a-input-group>
        </a-form-item>
    </a-modal>
    <!-- 设置范围结束 -->

    <div class="right">
        <a-spin :spinning="spinning.right">
            <template v-if="opened_topic_info.status">

                <div class="mes-header" style="padding: 14.2px 23px;">
                    <p class="topic-header-p">
                        <a-icon type="branches"></a-icon>&nbsp;&nbsp;{{ opened_topic_info.info.name }}
                        <em class="topic-series">({{ opened_series_info.info[opened_topic_info.series_index].name }})</em>
                        <div class="topic-op">
                            <em class="topic-date" style="margin-right: 15px;">{{ get_date(opened_topic_info.info.date) }}</em>

                            <template v-if="parseInt(user.id) == parseInt(opened_class_info.superid)">
                                <a-button type="default" @click="edit_info.visible.topic = true" style="margin-right: 5px;">
                                    <a-icon type="edit"></a-icon>
                                </a-button>
                                <a-button type="default" @click="delete_topic(opened_topic_info.info.id)">
                                    <a-icon style="color:#FF4040" type="delete"></a-icon>
                                </a-button>
                            </template>

                        </div>
                    </p>
                </div>

                <div class="topic-header" v-if="<?php echo $type; ?> == 2">
                    <div :style="opened_topic_info.section ? 'border-bottom: 2px solid #1890ff;color: #1890ff;' : ''" @click="opened_topic_info.section = !opened_topic_info.section;level_count = [0,0,0,0,0,0,0,0]">Table</div>
                    <div :style="opened_topic_info.section ? '' : 'border-bottom: 2px solid #1890ff;color: #1890ff;'" @click="opened_topic_info.section = !opened_topic_info.section;get_levels();">Chart</div>
                </div>

                <div class="topic-container">

                    <template v-if="opened_topic_info.section">

                        <div class="topic-table-add" :style="parseInt(user.id) == parseInt(opened_class_info.superid) ? '' : 'margin-bottom: 45px'">
                            <template v-if="parseInt(user.id) == parseInt(opened_class_info.superid)">
                                <a-button type="primary" @click="add.visible.record = true" v-if="(opened_topic_info.records_data).length < (opened_class_info.members).length" style="margin-right:10px">+ Add a Record</a-button>
                                <a-button @click="range.visible = true">Edit Grading Scale</a-button>
                            </template>
                            <a-popover title="Grading Scale" trigger="click">
                                <template slot="content">
                                    <p v-for="(scale,index) in range.scale" v-if="!!scale.max"><b>{{ range_sign[index].toUpperCase() }}</b> : {{ scale.max }}% ~ {{ scale.min }}%</p>
                                </template>
                                <a-button :style="parseInt(user.id) == parseInt(opened_class_info.superid) ? 'float:right' : 'float:left'">Grading Scale</a-button>
                            </a-popover>
                        </div>
                        <div class="table" v-if="(opened_topic_info.records_data).length !== 0 && <?php echo $type; ?> == 2">
                            <div class="table-header">
                                <div>Index</div>
                                <div>Date</div>
                                <div>Name</div>
                                <div @click="sortBy('score')">Score&nbsp;&nbsp;<a-icon type="sort-ascending" v-if="sort"></a-icon>
                                    <a-icon type="sort-descending" v-else></a-icon>
                                </div>
                                <div>Total</div>
                                <div>Level</div>
                                <template v-if="parseInt(user.id) == parseInt(opened_class_info.superid)">
                                    <div>Action</div>
                                </template>
                            </div>
                            <template v-for="(data,index) in opened_topic_info.records_data">
                                <div class="table-item">
                                    <div>{{ index + 1 }}</div>
                                    <div>{{ get_date(data.date) }}</div>
                                    <div>{{ data.name }}</div>
                                    <div>{{ data.score }}</div>
                                    <div>{{ data.total }}</div>
                                    <div v-if="!!opened_topic_info.grading">{{ data.level ? data.level.toUpperCase() : get_record_level(data.percent,index) }}</div>
                                    <div style="font-size:16px" v-else>
                                        <a-tooltip placement="topLeft">
                                            <template slot="title">
                                                <span>Grading Scale Needed</span>
                                            </template>
                                            <a-icon type="info-circle"></a-icon>
                                        </a-tooltip>
                                    </div>
                                    <template v-if="parseInt(user.id) == parseInt(opened_class_info.superid)">
                                        <div style="display:flex">
                                            <a-button size="small" @click="edit_record(index)" style="margin-right: 5px;">Edit</a-button>
                                            <a-button type="danger" size="small" @click="delete_record(data.id)">Delete</a-button>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- 成绩展示卡片 -->
                        <div v-if="<?php echo $type; ?> == 1">
                            <template v-for="(data,index) in opened_topic_info.records_data" v-if="data.user_id == user.id">
                                <div class="grade-card">
                                    <div class="info">
                                        <div class="author">
                                            <div>
                                                <img src="<?php echo $avatar; ?>">
                                            </div>
                                            <div class="author-info">
                                                <h3>{{ data.name }}</h3>
                                                <p>has scored:</p>
                                            </div>
                                        </div>
                                        <div class="date">
                                            <p>{{ get_date(data.date) }}</p>
                                        </div>
                                    </div>
                                    <div class="score">
                                        <div class="number">
                                            <p class="paper">
                                                <a-icon type="file-done"></a-icon>
                                            </p>
                                            <p class="paper-slogan">Score</p>
                                            <h1>{{ data.score }}</h1>
                                        </div>
                                        <div class="level">
                                            <p class="crown">
                                                <a-icon type="crown"></a-icon>
                                            </p>
                                            <p>Level</p>
                                            <h2>{{ data.level ? data.level.toUpperCase() : get_record_level(data.percent,index) }}</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-bottom">
                                    <a-button>
                                        <b>Total Score</b> : {{ data.total }}
                                    </a-button>
                                    <a-button>
                                        <b>Class Average Score</b> : {{ parseFloat(opened_topic_info.average).toFixed(1) }}
                                    </a-button>
                                </div>
                            </template>
                            <div v-html="stu_view()"></div>
                        </div>

                    </template>

                    <template v-else>
                        <ve-histogram :data="chartData" :settings="chartSettings" v-if="chartData.rows.length"></ve-histogram>
                        <p v-else style="font-size:16px;color:#999;letter-spacing:.5px">Chart is temporarily unavailable</p>
                        <a-button>
                            <b>Average Score</b> : {{ parseFloat(opened_topic_info.average).toFixed(1) }}
                        </a-button>
                        <br /><br />
                        <a-button v-for="(count,index) in level_count" v-if="count > 0" style="margin: 5px 10px 5px 0px;">
                            <b>{{ 'Level ' + range_sign[index].toUpperCase() }}</b> : {{ count + ' Students' }}
                        </a-button>
                    </template>

                </div>

            </template>

            <!-- Series 折线统计图 -->
            <template v-else-if="opened_stats_info.status">
                <div class="mes-header" style="padding: 14.2px 23px;">
                    <p class="topic-header-p">
                        <a-icon type="fork"></a-icon>&nbsp;&nbsp;{{ opened_stats_info.info.name }}
                        <em class="topic-series">Stats</em>
                        <div class="topic-op">
                            <em class="topic-date" style="margin-right: 15px;">{{ get_date(opened_stats_info.info.date) }}</em>
                        </div>
                    </p>
                </div>
                <div class="topic-container">
                    <ve-line :data="opened_stats_info.chartData_all" :settings="opened_stats_info.chartSettings_all" v-if="(opened_stats_info.chartData_all.rows).length !== 0"></ve-line>
                    <p v-else style="font-size:16px;color:#999;letter-spacing:.5px">Chart is temporarily unavailable</p>
                    <template v-if="parseInt(user.id) == parseInt(opened_class_info.superid)">
                        <a-select placeholder="Select a subject" defaultValue="all_topics" style="width: 120px;margin-right:10px" @change="handle_switch_user">
                            <a-select-option value="all_topics">All Topics</a-select-option>
                            <a-select-option v-for="person in opened_class_info.members" :value="person.id">{{ person.name }}</a-select-option>
                        </a-select>
                    </template>
                    <a-button @click="display_chart()" type="primary">Click to Display</a-button>
                    <template v-if="parseInt(user.id) == parseInt(opened_class_info.superid)">
                        <br /><br />
                    </template>
                    <a-button><b>Notice</b> : You might need to reload the web page to see updates</a-button>
                </div>
            </template>
            <!-- Series 折线统计图 -->
        </a-spin>
        <!-- 占位 -->
        <template v-if="!spinning.right && !opened_topic_info.status && !opened_stats_info.status">
            <div style="padding:20px 30px">
                <a-skeleton avatar :paragraph="{rows: 1}" v-for="i in 9"></a-skeleton>
            </div>
        </template>
        <!-- 占位 -->
    </div>



</div>







</div>
<link type="text/css" rel="stylesheet" href="../statics/css/chart.min.css">
<script type="text/javascript" src="../statics/js/chart.min.js"></script>
<script type="text/javascript" src="../statics/js/chart.index.min.js"></script>
<script type="text/javascript" src="../src/grades.js"></script>


<?php require 'pro_footer.php'; ?>