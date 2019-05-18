import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Container from 'react-bootstrap/Container';
import ReactTable from "react-table";
import {
    Col,
    Form,
    Row,
    ButtonToolbar,
    Button, Alert
} from "react-bootstrap";
import CardLoaderReact from './CardLoader.react';
import {
    STATUS_CODE_OK,
    URL_API_PLAY_LIST_DETAIL,
    URL_API_PLAY_LIST_DETAIL_VIDEO_LIST,
    URL_PLAYLIST_ITEM_CREATE,
    URL_API_PLAY_LIST_DETAIL_DELETE_VIDEO,
    URL_API_PLAY_LIST_DETAIL_UPDATE_POSITION
} from '../util/constant';
import { fetch } from '../util/util';
import trans from '../lang/index';
import Pagination from "./Pagination.react";
import InputGroupReact from './InputGroup.react';
import TextareaGroupReact from './TextareaGroup.react';
import LabelToolTip from './LabelToolTip.react';
import matchSorter from 'match-sorter';
import Icon from "./Icon.react";
import 'react-table/react-table.css';

class DetailPlayList extends Component {

    constructor(props) {
        super(props);
        this.state = {
            loadingPlayListInfo: false,
            loadingVideoInfo: true,
            playListInfo: {},
            listVideo: [],
            message: "",
            messageType: "",
            linkYoutubeVideo: "",
        }
    }

    componentWillMount() {
        this.initGetData();
    }

    initGetData = () => {
        var urlParams = new URLSearchParams(window.location.search);
        if (!urlParams.has('id')) {
            window.location.href = "/admin/playlist";
        }

        const id = urlParams.get('id');
        this.getDataPlaylist(id);

    }

    getDataPlaylist = (id) => {
        this.setState({ loadingPlayListInfo: true });
        fetch(URL_API_PLAY_LIST_DETAIL + "?id=" + id,
            'get')
            .then(result => {
                this.setState({ loadingPlayListInfo: false });
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    const data = result.data.body.data;
                    if (data == null || data.id == "") {
                        window.location.href = "/admin/playlist";
                    }
                    console.log(data);
                    this.setState({ playListInfo: data });
                    this.getDataVideo(data.id);
                }
            })
            .catch(error => {
                console.log(error)
                this.setState({ loadingPlayListInfo: false });
            });
    }

    getDataVideo = (playlist_id) => {
        this.setState({ loadingVideoInfo: true });
        fetch(URL_API_PLAY_LIST_DETAIL_VIDEO_LIST + "?playlist_id=" + playlist_id,
            'get')
            .then(result => {
                console.log(result.data.body)
                this.setState({ loadingVideoInfo: false });
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    const data = result.data.body.data;
                    this.setState({ listVideo: data });
                }
            })
            .catch(error => {
                console.log(error)
                this.setState({ loadingVideoInfo: false });
            });
    }

    showAlert = (message, type) => {
        this.setState({
            message: message,
            messageType: type
        });
    }

    hideAlert = () => {
        this.showAlert(null, 'info');
    }

    formatNumber = (num) => {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
    }

    getYouTubeGetID = (url) => {
        var ID = '';
        url = url.replace(/(>|<)/gi, '').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
        if (url[2] !== undefined) {
            ID = url[2].split(/[^0-9a-z_\-]/i);
            ID = ID[0];
        }
        else {
            ID = url;
        }

        return ID;
    }

    submitAddVideo = () => {
        const linkUrl = this.state.linkYoutubeVideo;
        const playListInfo = this.state.playListInfo;
        if (linkUrl == null || linkUrl == "") {
            this.showAlert(trans.get("detailplaylist.is_valid_message_insert_video"), "danger");
            jQuery("input[type='text'][name='id_group_title']").focus();
            return;
        }
        const id = this.getYouTubeGetID(linkUrl);
        this.setState({ loadingVideoInfo: true });
        fetch(URL_PLAYLIST_ITEM_CREATE,
            'post',
            {
                "playlist_id": playListInfo.id,
                "video_uid": id
            })
            .then(result => {
                this.setState({ loadingVideoInfo: false });
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    this.showAlert(trans.get("detailplaylist.success"), 'success');
                    this.getDataVideo(playListInfo.id);
                } else {
                    this.showAlert(trans.get("detailplaylist.fail"), 'danger');
                }
            })
            .catch(error => {
                console.log(error);
                this.showAlert(trans.get("detailplaylist.fail"), 'danger');
                this.setState({ loadingVideoInfo: false });
            });
    }

    redirectYoutube = (data) => {
        const url = `https://www.youtube.com/watch?v=${data.video_uid}&list=${this.state.playListInfo.uid}`;
        window.open(url);
    }

    handlerDelete = (data) => {
        const playListInfo = this.state.playListInfo;
        this.setState({ loadingVideoInfo: true });
        fetch(URL_API_PLAY_LIST_DETAIL_DELETE_VIDEO + `?id=${data.id}&video_uid=${data.uid}&position=${data.position}&channel_id=${playListInfo.channel_id}&playlist_id=${playListInfo.id}`,
            'delete')
            .then(result => {
                this.setState({ loadingVideoInfo: false });
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    this.showAlert(trans.get("detailplaylist.success"), 'success');
                    this.getDataVideo(playListInfo.id);
                } else {
                    this.showAlert(trans.get("detailplaylist.fail"), 'danger');
                }
            })
            .catch(error => {
                console.log(error);
                this.showAlert(trans.get("detailplaylist.fail"), 'danger');
                this.setState({ loadingVideoInfo: false });
            });
    }

    handlerEdit = (data) => {
        const position = prompt(`Vị trí của video hiện tại là ${data.position + 1}. Bạn muốn thay đổi thành`, "");
        const { listVideo, playListInfo } = this.state;
        console.log(position);
        if (!position) {

            return;
        }
        const valuePosition = parseInt(position);
        if (!valuePosition) {
            alert("Nhập vị trí video không hợp lệ");

            return;
        }

        if (!valuePosition || valuePosition < 1 || valuePosition > (listVideo.length)) {
            alert("Nhập vị trí video không hợp lệ");

            return;
        }

        if ((valuePosition - 1) == data.position) {
            return;
        }

        this.setState({ loadingVideoInfo: true });
        fetch(URL_API_PLAY_LIST_DETAIL_UPDATE_POSITION,
            'put',
            {
                "id": data.id,
                "video_uid": data.uid,
                "video_video_uid": data.video_uid,
                "position_old": data.position,
                "position_new": valuePosition,
                "channel_id": playListInfo.channel_id,
                "playlist_id": playListInfo.id,
                "playlist_uid": playListInfo.uid
            })
            .then(result => {
                this.setState({ loadingVideoInfo: false });
                console.log(result.data.body);
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    this.showAlert(trans.get("detailplaylist.success"), 'success');
                    this.getDataVideo(playListInfo.id);
                } else {
                    this.showAlert(trans.get("detailplaylist.fail"), 'danger');
                }
            })
            .catch(error => {
                console.log(error);
                this.showAlert(trans.get("detailplaylist.fail"), 'danger');
                this.setState({ loadingVideoInfo: false });
            });

    }

    render() {
        const { playListInfo, message, listVideo, linkYoutubeVideo, messageType } = this.state;
        const columns = [
            {
                Header: trans.get('keyword.title'),
                accessor: 'title',
                minWidth: 380,
                filterMethod: (filter, rows) =>
                    matchSorter(rows, filter.value, { keys: ["title"] }),
                filterAll: true
            },
            {
                Header: trans.get('detailplaylist.view_count'),
                accessor: 'view_count',
                maxWidth: 120,
                className: 'd-flex justify-content-end',
                filterMethod: (filter, rows) =>
                    matchSorter(rows, filter.value, { keys: ["view_count"] }),
                filterAll: true,
                Cell: row => (
                    this.formatNumber(row.original.view_count)
                )
            },
            {
                Header: trans.get('detailplaylist.like_count'),
                accessor: 'like_count',
                maxWidth: 120,
                className: 'd-flex justify-content-end',
                filterMethod: (filter, rows) =>
                    matchSorter(rows, filter.value, { keys: ["like_count"] }),
                filterAll: true,
                Cell: row => (
                    this.formatNumber(row.original.like_count)
                )
            },

            {
                Header: trans.get('detailplaylist.dislike_count'),
                accessor: 'dislike_count',
                maxWidth: 120,
                className: 'd-flex justify-content-end',
                filterMethod: (filter, rows) =>
                    matchSorter(rows, filter.value, { keys: ["dislike_count"] }),
                filterAll: true,
                Cell: row => (
                    this.formatNumber(row.original.dislike_count)
                )
            },
            // {
            //     Header: trans.get('keyword.status'),
            //     accessor: 'status_video',
            //     maxWidth: 200,
            //     Cell: ({ value }) => (value === 'public' ? trans.get('detailplaylist.status_video_normal') : trans.get('detailplaylist.status_video_block')),
            //     filterMethod: (filter, row) => {
            //         if (filter.value === "all") {
            //             return true;
            //         }
            //         if (filter.value === "false") {
            //             return row.status_video === 'private';
            //         }
            //         return row.status_video === 'public';
            //     },
            //     Filter: ({ filter, onChange }) =>
            //         <Form.Control as="select"
            //             onChange={event => onChange(event.target.value)}
            //             value={filter ? filter.value : "all"}
            //             style={{ padding: "4px", height: "unset" }}
            //         >
            //             <option value="all">{trans.get('detailplaylist.all_status_video')}</option>
            //             <option value="true">{trans.get('detailplaylist.status_video_normal')}</option>
            //             <option value="false">{trans.get('detailplaylist.status_video_block')}</option>
            //         </Form.Control>
            // },
            {
                Header: '',
                minWidth: 120,
                sortable: false,
                filterable: false,
                className: 'd-flex justify-content-center',
                Cell: row => (
                    <ButtonToolbar>
                        <Button onClick={e => this.handlerEdit(row.original)} variant="primary" size="sm">
                            <Icon name='fe fe-edit-2' />
                        </Button >
                        <Button onClick={e => this.redirectYoutube(row.original)} variant="warning" size="sm" className="ml-1">
                            <Icon name='fe fe-link' />
                        </Button>
                        <Button onClick={e => this.handlerDelete(row.original)} variant="danger" size="sm" className="ml-1">
                            <Icon name='fe fe-trash' />
                        </Button>
                    </ButtonToolbar>
                )
            }
        ];
        return (
            <Container>
                <div className="page-header">
                    <div className="page-title">
                        {trans.get('detailplaylist.title')}
                    </div>
                </div>
                <Row className="row-cards">
                    <Col lg="12">
                        <CardLoaderReact
                            isLoading={this.state.loadingPlayListInfo}
                            title={trans.get("detailplaylist.title_playlist_info")}>
                            <InputGroupReact
                                name='title'
                                label={trans.get('message.label_title_playlist')}
                                type="text"
                                defaultValue={playListInfo.title}
                                value={playListInfo.title}
                                readOnly={true}
                            />
                            <TextareaGroupReact
                                rows={5}
                                name="description"
                                title='description'
                                label={trans.get('message.label_description_playlist')}
                                defaultValue={playListInfo.description}
                                value={playListInfo.description}
                                readOnly={true}
                            />
                        </CardLoaderReact>
                    </Col>
                    <Col lg="12">
                        <CardLoaderReact
                            isLoading={this.state.loadingVideoInfo}
                            title={trans.get("detailplaylist.title_insert_video")}>
                            {message ?
                                <Alert onClose={this.hideAlert} dismissible variant={messageType}>
                                    <p>{message}</p></Alert> : ''}

                            <div className="form-group w-75 d-block">
                                <LabelToolTip
                                    title={trans.get("detailplaylist.title_insert_video")}
                                    id="title-template-right"
                                />
                                <div className="input-group">
                                    <input name="id_group_title"
                                        type="text" className="form-control"
                                        value={linkYoutubeVideo}
                                        placeholder="https://www.youtube.com/watch?v=xxxxxxxxxxx"
                                        onChange={(e) => { this.setState({ linkYoutubeVideo: e.target.value }) }}
                                    />
                                    <span className="input-group-append">
                                        <button
                                            className="btn btn-primary"
                                            onClick={this.submitAddVideo}>
                                            {trans.get("detailplaylist.button_title_insert_video")}
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <ReactTable
                                filterable
                                PaginationComponent={Pagination}
                                noDataText={trans.get('message.no_result')}
                                columns={columns}
                                data={listVideo}
                                defaultPageSize={10}
                                className=""
                            />
                        </CardLoaderReact>
                    </Col>
                </Row>
            </Container>
        );
    }
}

if (document.getElementById('detail-playlist')) {
    ReactDOM.render(<DetailPlayList />, document.getElementById('detail-playlist'));
}