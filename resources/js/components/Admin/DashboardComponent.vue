<template>
    <div>
        <div class="container-fluid">
            <!-- Page Heading -->
            <div
                class="d-sm-flex align-items-center justify-content-between mb-4"
            >
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            </div>

            <!-- teacher stats -->
            <div v-if="teacher_stats">
                <div
                    class="d-sm-flex align-items-center justify-content-between mb-4"
                >
                    <h2>Your courses statistics</h2>
                </div>
                <div class="row">
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs font-weight-bold text-primary text-uppercase mb-1"
                                        >
                                            Completed courses
                                        </div>
                                        <div
                                            class="h5 mb-0 font-weight-bold text-gray-800"
                                        >
                                            {{
                                                teacher_stats.completed_courses
                                            }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i
                                            class="fas fa-book fa-2x text-gray-300"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs font-weight-bold text-success text-uppercase mb-1"
                                        >
                                            Total checklist requirements
                                        </div>
                                        <div
                                            class="h5 mb-0 font-weight-bold text-gray-800"
                                        >
                                            {{ teacher_stats.checklist_total }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i
                                            class="fas fa-clipboard-list fa-2x text-gray-300"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Requests Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs font-weight-bold text-warning text-uppercase mb-1"
                                        >
                                            Completed checklist requirements
                                        </div>
                                        <div
                                            class="h5 mb-0 font-weight-bold text-gray-800"
                                        >
                                            {{
                                                teacher_stats.checklist_checked
                                            }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i
                                            class="fas fa-clipboard-list fa-2x text-gray-300"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs font-weight-bold text-info text-uppercase mb-1"
                                        >
                                            Overall checklist completion
                                            percentage
                                        </div>
                                        <div
                                            class="row no-gutters align-items-center"
                                        >
                                            <div class="col-auto">
                                                <div
                                                    class="h5 mb-0 mr-3 font-weight-bold text-gray-800"
                                                >
                                                    {{
                                                        teacher_stats.checklist_percentage
                                                    }}%
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div
                                                    class="progress progress-sm mr-2"
                                                >
                                                    <div
                                                        class="progress-bar bg-info"
                                                        role="progressbar"
                                                        :style="{
                                                            width:
                                                                teacher_stats.checklist_percentage +
                                                                '%'
                                                        }"
                                                        aria-valuenow="50"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100"
                                                    ></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i
                                            class="fas fa-clipboard-list fa-2x text-gray-300"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- tai stats -->
            <div v-if="tai_stats">
                <div
                    class="d-sm-flex align-items-center justify-content-between mb-4"
                >
                    <h2>Your assigned teacher's statistics</h2>
                </div>
                <div class="row">
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs font-weight-bold text-primary text-uppercase mb-1"
                                        >
                                            Completed courses
                                        </div>
                                        <div
                                            class="h5 mb-0 font-weight-bold text-gray-800"
                                        >
                                            {{ tai_stats.completed_courses }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i
                                            class="fas fa-book fa-2x text-gray-300"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs font-weight-bold text-success text-uppercase mb-1"
                                        >
                                            Total checklist requirements
                                        </div>
                                        <div
                                            class="h5 mb-0 font-weight-bold text-gray-800"
                                        >
                                            {{ tai_stats.checklist_total }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i
                                            class="fas fa-clipboard-list fa-2x text-gray-300"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Requests Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs font-weight-bold text-warning text-uppercase mb-1"
                                        >
                                            Completed checklist requirements
                                        </div>
                                        <div
                                            class="h5 mb-0 font-weight-bold text-gray-800"
                                        >
                                            {{ tai_stats.checklist_checked }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i
                                            class="fas fa-clipboard-list fa-2x text-gray-300"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs font-weight-bold text-info text-uppercase mb-1"
                                        >
                                            Overall checklist completion
                                            percentage
                                        </div>
                                        <div
                                            class="row no-gutters align-items-center"
                                        >
                                            <div class="col-auto">
                                                <div
                                                    class="h5 mb-0 mr-3 font-weight-bold text-gray-800"
                                                >
                                                    {{
                                                        tai_stats.checklist_percentage
                                                    }}%
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div
                                                    class="progress progress-sm mr-2"
                                                >
                                                    <div
                                                        class="progress-bar bg-info"
                                                        role="progressbar"
                                                        :style="{
                                                            width:
                                                                tai_stats.checklist_percentage +
                                                                '%'
                                                        }"
                                                        aria-valuenow="50"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100"
                                                    ></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i
                                            class="fas fa-clipboard-list fa-2x text-gray-300"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["selectedSession"],
    watch: {
        selectedSession: function(val, oldVal) {
            this.getStats(val.id);
        }
    },
    data() {
        return {
            base_path: "/api/",
            teacher_stats: false,
            tai_stats: false,
            permissions: []
        };
    },
    mounted() {
        var localPermission = localStorage.getItem("permissions");
        if (localPermission != null)
            this.permissions = localPermission.split(",");

        if (!this.permissions.includes("view_dashboard")) {
            this.$router.push("/portal/manage-users");
        }

        this.getStats(this.selectedSession.id);
    },
    methods: {
        getStats(s = 0) {
            let path = this.base_path + "dashboard";
            if (s !== 0) {
                path += "?session=" + s;
            }
            axios
                .get(path)
                .then(res => {
                    this.teacher_stats = res.data.stats.teacher;
                    this.tai_stats = res.data.stats.tai;
                })
                .catch(error => {
                    console.log("error: ", error);
                });
        }
    }
};
</script>
