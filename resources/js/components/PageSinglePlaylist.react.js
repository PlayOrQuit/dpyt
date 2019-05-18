import React from 'react';
import ReactDOM from 'react-dom';
import {
    Container,
    Row,
    Col,
    Button,
    Alert,
} from 'react-bootstrap';
import SelectGroupReact from './SelectGroup.react';
import TagsGroupReact from './TagsGroup.react';
import InputGroupReact from './InputGroup.react';
import TextareaGroupReact from './TextareaGroup.react';
import trans from '../lang';
import {fetch} from '../util/util';
import {
    URL_PLAYLIST_CREATE,
    URL_CHANNEL_GET_BY_STATUS,
    STATUS_CODE_OK,
    STATUS_CODE_FIELD_ERROR,
    STATUS_CODE_DB_ERROR
} from '../util/constant';
import CardLoaderReact from "./CardLoader.react";

class PageSinglePlaylist extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            idLoading: false,
            message: null,
            messageType: 'info',
            channels: [],
            channelValue: null,
            channelError: null,
            keywordValue: [],
            keywordError: null,
            titlePlaylist: '',
            titlePlaylistError: null,
            descriptionPlaylist: '',
            descriptionPlaylistError: null,
            enableFilterVideo: false,
            enableFilterVideoTime: false,
            enableFilterVideoDuration: false,
            enableFilterVideoView: false,
            enableFilterVideoLike: false,
            enableFilterVideoDislike: false,
            valueFilterEqualTime: "0",
            valueFilterVideoTime: "",
            valueFilterVideoDuration: "",
            valueFilterVideoView: "",
            valueFilterVideoLike: "",
            valueFilterVideoDislike: "",
            valueFilterVideoTimeError: null,
            valueFilterVideoDurationError: null,
            valueFilterVideoViewError: null,
            valueFilterVideoLikeError: null,
            valueFilterVideoDislikeError: null,
            channelSubscribe: null,
            channelSubscribeError: null
        }
    }

    componentWillMount() {
        this.fetchDataChannel();
    }

    // componentDidMount() {
    //     // fetch(URL_PLAYLIST_ITEM_CREATE, 'post', {
    //     //     playlist_id: 1,
    //     //     video_uid: 'JOiHdtlMkOs'
    //     // })
    //     //     .then(result => console.log(result))
    //     //     .catch(error => console.log(error))
    // }

    setError(k, msg) {
        if (k === 'channelValue') {
            this.setState({'channelError': msg});
        } else if (k === 'keywordValue') {
            this.setState({'keywordError': msg});
        } else if (k === 'titlePlaylist') {
            this.setState({'titlePlaylistError': msg});
        } else if (k === 'descriptionPlaylist') {
            this.setState({'descriptionPlaylistError': msg});
        } else if (k === 'valueFilterVideoTime') {
            this.setState({'valueFilterVideoTimeError': msg});
        } else if (k === 'valueFilterVideoDuration') {
            this.setState({'valueFilterVideoDurationError': msg});
        } else if (k === 'valueFilterVideoView') {
            this.setState({'valueFilterVideoViewError': msg});
        } else if (k === 'valueFilterVideoLike') {
            this.setState({'valueFilterVideoLikeError': msg});
        } else if (k === 'valueFilterVideoDislike') {
            this.setState({'valueFilterVideoDislikeError': msg});
        }
    }

    fetchDataChannel() {
        fetch(URL_CHANNEL_GET_BY_STATUS, 'get', {})
            .then(result => {
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    let results = result.data.body.data;
                    let newChannels = [];
                    results.map((val, index) => {
                        newChannels.push({
                            value: val.id,
                            label: val.title,
                            path: val.thumbnail
                        })
                    })
                    this.setState({channels: newChannels});
                }
            })
            .catch(error => console.log(error));
    }

    showAlert = (message, type) => {
        this.setState({message: message, messageType: type});
    }
    hideAlert = () => {
        this.showAlert(null, 'info');
    }
    handlerChangeKeyword = (newTags) => {
        if (newTags.length <= 0) {
            this.setError('keywordValue', trans.get('validation.required', {attribute: trans.get('message.label_keyword_playlist')}));
            this.setState({
                keywordValue: newTags
            })
        } else {
            this.setState({
                keywordError: null,
                keywordValue: newTags
            })
        }
    }

    handlerChangeChannel = (item) => {
        if (item) {
            this.setState({
                channelError: null,
                channelValue: item
            });
        } else {
            this.setError('channelValue', trans.get('validation.required', {attribute: trans.get('message.label_channel')}));
        }
    }

    handlerChangeTitle = (e) => {
        if (e.target.value === '') {
            this.setError('titlePlaylist', trans.get('validation.required', {attribute: trans.get('message.label_title_playlist')}));
            this.setState({
                titlePlaylist: e.target.value
            });
        } else {
            this.setState({
                titlePlaylistError: null,
                titlePlaylist: e.target.value
            });
        }

    }

    handlerChangeDescription = (e) => {
        if (e.target.value === '') {
            this.setError('descriptionPlaylist', trans.get('validation.required', {attribute: trans.get('message.label_description_playlist')}));
            this.setState({
                descriptionPlaylist: e.target.value
            });
        } else {
            this.setState({
                descriptionPlaylistError: null,
                descriptionPlaylist: e.target.value
            });
        }
    }

    handlerChangeChannelSubscribe = (e) => {
        this.setState({
            channelSubscribeError: null,
            channelSubscribe: e.target.value
        });
    }

    submitDataResult(data) {
        if (data.statusCode === STATUS_CODE_OK) {
            this.showAlert(trans.get('message.create_success'), 'success');
        } else if (data.statusCode === STATUS_CODE_FIELD_ERROR) {
            if (data.field_errors.channel_id) {
                this.setError('channelValue', data.field_errors.channel_id[0]);
            } else if (data.field_errors.keywords) {
                this.setError('keywordValue', data.field_errors.keywords[0]);
            } else if (data.field_errors.title) {
                this.setError('titlePlaylist', data.field_errors.title[0]);
            } else if (data.field_errors.description) {
                this.setError('descriptionPlaylist', data.field_errors.description[0]);
            } else if (data.field_errors.filter_by_date) {
                this.setError('valueFilterVideoTime', data.field_errors.filter_by_date[0]);
            } else if (data.field_errors.filter_by_duration) {
                this.setError('valueFilterVideoDuration', data.field_errors.filter_by_duration[0]);
            } else if (data.field_errors.filter_by_view) {
                this.setError('valueFilterVideoView', data.field_errors.filter_by_view[0]);
            } else if (data.field_errors.filter_by_like) {
                this.setError('valueFilterVideoLike', data.field_errors.filter_by_like[0]);
            } else if (data.field_errors.filter_by_dislike) {
                this.setError('valueFilterVideoDislike', data.field_errors.filter_by_dislike[0]);
            }
        } else if (data.statusCode === STATUS_CODE_DB_ERROR) {
            this.showAlert(trans.get('message.create_failed'), 'danger');
        } else {
            this.showAlert(trans.get('message.create_failed'), 'danger');
        }
    }

    submitData = () => {
        const {
            channelValue,
            keywordValue,
            titlePlaylist,
            descriptionPlaylist,
            enableFilterVideo,
            enableFilterVideoTime,
            enableFilterVideoDuration,
            enableFilterVideoView,
            enableFilterVideoLike,
            enableFilterVideoDislike,
            valueFilterEqualTime,
            valueFilterVideoTime,
            valueFilterVideoDuration,
            valueFilterVideoView,
            valueFilterVideoLike,
            valueFilterVideoDislike,
            channelSubscribe

        } = this.state;
        let isReq = true;
        if (channelValue == null) {
            isReq = false;
            this.setError('channelValue', trans.get('validation.required', {attribute: trans.get('message.label_channel')}));
        }
        if (keywordValue.length === 0) {
            isReq = false;
            this.setError('keywordValue', trans.get('validation.required', {attribute: trans.get('message.label_keyword_playlist')}));
        }
        if (titlePlaylist === '') {
            isReq = false;
            this.setError('titlePlaylist', trans.get('validation.required', {attribute: trans.get('message.label_title_playlist')}));
        }
        if (enableFilterVideo) {
            if (enableFilterVideoTime) {
                if (valueFilterVideoTime === '') {
                    isReq = false;
                    this.setError('valueFilterVideoTime', trans.get('validation.required', {attribute: trans.get('multipleplaylist.enable_filter_video_time')}));
                }
            }
            if (enableFilterVideoDuration) {
                if (valueFilterVideoDuration === '') {
                    isReq = false;
                    this.setError('valueFilterVideoDuration', trans.get('validation.required', {attribute: trans.get('multipleplaylist.enable_filter_video_duration')}));
                }
            }
            if (enableFilterVideoView) {
                if (valueFilterVideoView === '') {
                    isReq = false;
                    this.setError('valueFilterVideoView', trans.get('validation.required', {attribute: trans.get('multipleplaylist.enable_filter_video_view')}));
                }
            }
            if (enableFilterVideoLike) {
                if (valueFilterVideoLike === '') {
                    isReq = false;
                    this.setError('valueFilterVideoLike', trans.get('validation.required', {attribute: trans.get('multipleplaylist.enable_filter_video_like')}));
                }
            }
            if (enableFilterVideoDislike) {
                if (valueFilterVideoDislike === '') {
                    isReq = false;
                    this.setError('valueFilterVideoDislike', trans.get('validation.required', {attribute: trans.get('multipleplaylist.enable_filter_video_disklike')}));
                }
            }
        }
        if (isReq) {
            let newKeywords = [];
            keywordValue.map(val => {
                newKeywords.push(val.text);
            });
            this.setState({isLoading: true});
            let dataReq = {
                channel_id: channelValue.value,
                keywords: newKeywords,
                title: titlePlaylist,
                status_filter: enableFilterVideo
            }
            if(channelSubscribe !== ''){
                dataReq.channel_subscribe = channelSubscribe;
            }
            if (descriptionPlaylist !== '') {
                dataReq.description = descriptionPlaylist;
            }
            if (valueFilterVideoTime !== '') {
                dataReq.filter_by_date = valueFilterVideoTime;
            }
            if (valueFilterEqualTime !== '') {
                dataReq.filter_by_date_status = valueFilterEqualTime === '1';
            }
            if (valueFilterVideoDuration !== '') {
                dataReq.filter_by_duration = valueFilterVideoDuration;
            }
            if (valueFilterVideoView !== '') {
                dataReq.filter_by_view = valueFilterVideoView;
            }
            if (valueFilterVideoLike !== '') {
                dataReq.filter_by_like = valueFilterVideoLike;
            }
            if (valueFilterVideoDislike !== '') {
                dataReq.filter_by_dislike = valueFilterVideoDislike;
            }

            fetch(URL_PLAYLIST_CREATE, 'post', dataReq)
                .then(result => {
                    this.setState({isLoading: false});
                    this.submitDataResult(result.data.body);
                })
                .catch(error => {
                    this.setState({isLoading: false});
                    console.log(error)
                });
        }

    }

    render() {
        const {
            channels,
            channelValue,
            channelError,
            keywordValue,
            keywordError,
            titlePlaylist,
            titlePlaylistError,
            descriptionPlaylist,
            descriptionPlaylistError,
            valueFilterVideoTimeError,
            valueFilterVideoDurationError,
            valueFilterVideoViewError,
            valueFilterVideoLikeError,
            valueFilterVideoDislikeError,
            channelSubscribe,
            channelSubscribeError

        } = this.state;
        const now = new Date();
        return (
            <Container>
                <div className="page-header">
                    <div className="page-title">
                        {trans.get('template.menu_sub_add_playlist')}
                    </div>
                </div>
                <Row className="row-cards">
                    <Col lg="12">
                        <CardLoaderReact isLoading={this.state.isLoading}>
                            {this.state.message ?
                                <Alert onClose={this.hideAlert} dismissible variant={this.state.messageType}>
                                    <p>{this.state.message}</p></Alert> : ''}
                            <SelectGroupReact
                                label={trans.get('message.label_channel')}
                                name="channel"
                                image={true}
                                options={channels}
                                onChange={this.handlerChangeChannel}
                                value={channelValue}
                                error={channelError}
                            />
                            <TagsGroupReact
                                tags={keywordValue}
                                label={trans.get('message.label_keyword_playlist')}
                                onChange={this.handlerChangeKeyword}
                                error={keywordError}
                            />
                            <InputGroupReact
                                name='channel_subscribe'
                                label={trans.get('message.channel_subscribe')}
                                type="text"
                                onChange={this.handlerChangeChannelSubscribe}
                                defaultValue={channelSubscribe}
                                value={channelSubscribe}
                                error={channelSubscribeError}
                            />
                            <InputGroupReact
                                name='title'
                                label={trans.get('message.label_title_playlist')}
                                type="text"
                                onChange={this.handlerChangeTitle}
                                defaultValue={titlePlaylist}
                                value={titlePlaylist}
                                error={titlePlaylistError}
                            />
                            <TextareaGroupReact
                                rows={5}
                                name="description"
                                title='description'
                                label={trans.get('message.label_description_playlist')}
                                onChange={this.handlerChangeDescription}
                                defaultValue={descriptionPlaylist}
                                value={descriptionPlaylist}
                                error={descriptionPlaylistError}
                            />

                            <div className="form-group">
                                <label className="custom-control custom-checkbox w-auto d-inline-block">
                                    <input type="checkbox" name="filter_video"
                                           className="custom-control-input"
                                           checked={this.state.enableFilterVideo}
                                           onChange={(e) => {
                                               this.setState({enableFilterVideo: e.target.checked})
                                           }}
                                    />
                                    <span className="custom-control-label">
                                            {trans.get("multipleplaylist.enable_filter_video")}
                                        </span>
                                </label>
                                {
                                    this.state.enableFilterVideo == true &&
                                    <>
                                        <Col lg="4">
                                            <label className="custom-control custom-checkbox w-auto d-inline-block">
                                                <input type="checkbox" name="filter_video"
                                                       className="custom-control-input"
                                                       checked={this.state.enableFilterVideoTime}
                                                       onChange={(e) => {
                                                           this.setState({enableFilterVideoTime: e.target.checked})
                                                       }}/>
                                                <span className="custom-control-label">
                                                        {trans.get("multipleplaylist.enable_filter_video_time")}
                                                    </span>
                                            </label>
                                            {
                                                this.state.enableFilterVideoTime == true &&
                                                <>
                                                    <div className="input-group">
                                                            <span className="input-group-prepend">
                                                                <span className="input-group-text"
                                                                      style={{padding: "5px 0"}}>
                                                                    <select className="custom-input-group-prepend"
                                                                            value={this.state.valueFilterEqualTime}
                                                                            onChange={(e) => {
                                                                                this.setState({
                                                                                    valueFilterEqualTime: e.target.value
                                                                                })
                                                                            }}
                                                                    >
                                                                        <option
                                                                            value="0">{trans.get("multipleplaylist.filter_equal_lower")}</option>
                                                                        <option
                                                                            value="1">{trans.get("multipleplaylist.filter_equal_higher")}</option>
                                                                    </select>
                                                                </span>
                                                            </span>
                                                        <input
                                                            className="form-control d-inline-block w-auto"
                                                            type="text"
                                                            data-mask="0000/00/00" data-mask-clearifnotmatch="true"
                                                            placeholder={now.getFullYear() + '-' + (now.getMonth() + 1) < 10 ? '0' : '' +(now.getMonth() + 1) + '-' + now.getDate()}
                                                            autoComplete="off"
                                                            maxLength="10"
                                                            value={this.state.valueFilterVideoTime}
                                                            onChange={(e) => {
                                                                this.setState({
                                                                    valueFilterVideoTime: e.target.value,
                                                                    valueFilterVideoTimeError: null
                                                                })
                                                            }}
                                                        />
                                                        <span className="input-group-append">
                                                                <span className="input-group-text"><i
                                                                    className="fe fe-calendar"></i></span>
                                                            </span>
                                                        {valueFilterVideoTimeError ? <div className="invalid-feedback"
                                                                                          style={{display: 'block'}}>{valueFilterVideoTimeError}</div> : ""}
                                                    </div>
                                                </>
                                            }
                                        </Col>
                                        <Col lg="3">
                                            <label className="custom-control custom-checkbox w-auto d-inline-block">
                                                <input type="checkbox" name="filter_video"
                                                       className="custom-control-input"
                                                       checked={this.state.enableFilterVideoDuration}
                                                       onChange={(e) => {
                                                           this.setState({enableFilterVideoDuration: e.target.checked})
                                                       }}/>
                                                <span className="custom-control-label">
                                                        {trans.get("multipleplaylist.enable_filter_video_duration")}
                                                    </span>
                                            </label>
                                            {
                                                this.state.enableFilterVideoDuration == true &&
                                                <div className="input-group">
                                                    <input
                                                        className="form-control d-inline-block w-auto ml-2"
                                                        type="number"
                                                        placeholder={trans.get("multipleplaylist.enable_filter_video_duration_placeholder")}
                                                        value={this.state.valueFilterVideoDuration}
                                                        onChange={(e) => {
                                                            this.setState({
                                                                valueFilterVideoDuration: e.target.value,
                                                                valueFilterVideoDurationError: null
                                                            })
                                                        }}
                                                    />
                                                    <span className="input-group-append">
                                                            <span className="input-group-text"><i
                                                                className="fe fe-clock"></i></span>
                                                        </span>
                                                    {valueFilterVideoDurationError ? <div className="invalid-feedback"
                                                                                          style={{display: 'block'}}>{valueFilterVideoDurationError}</div> : ""}
                                                </div>
                                            }
                                        </Col>
                                        <Col lg="3">
                                            <label className="custom-control custom-checkbox w-auto d-inline-block">
                                                <input type="checkbox" name="filter_video"
                                                       className="custom-control-input"
                                                       checked={this.state.enableFilterVideoView}
                                                       onChange={(e) => {
                                                           this.setState({enableFilterVideoView: e.target.checked})
                                                       }}/>
                                                <span className="custom-control-label">
                                                        {trans.get("multipleplaylist.enable_filter_video_view")}
                                                    </span>
                                            </label>
                                            {
                                                this.state.enableFilterVideoView == true &&
                                                <div className="input-group">
                                                    <input
                                                        className="form-control d-inline-block w-auto ml-2"
                                                        type="number"
                                                        placeholder={trans.get("multipleplaylist.enable_filter_video_view")}
                                                        value={this.state.valueFilterVideoView}
                                                        onChange={(e) => {
                                                            this.setState({
                                                                valueFilterVideoView: e.target.value,
                                                                valueFilterVideoViewError: null
                                                            })
                                                        }}
                                                    />
                                                    <span className="input-group-append">
                                                            <span className="input-group-text"><i
                                                                className="fe fe-play-circle"></i></span>
                                                        </span>
                                                    {valueFilterVideoViewError ? <div className="invalid-feedback"
                                                                                      style={{display: 'block'}}>{valueFilterVideoViewError}</div> : ""}

                                                </div>
                                            }
                                        </Col>
                                        <Col lg="3">
                                            <label className="custom-control custom-checkbox w-auto d-inline-block">
                                                <input type="checkbox" name="filter_video"
                                                       className="custom-control-input"
                                                       checked={this.state.enableFilterVideoLike}
                                                       onChange={(e) => {
                                                           this.setState({enableFilterVideoLike: e.target.checked})
                                                       }}/>
                                                <span className="custom-control-label">
                                                        {trans.get("multipleplaylist.enable_filter_video_like")}
                                                    </span>
                                            </label>
                                            {
                                                this.state.enableFilterVideoLike == true &&
                                                <div className="input-group">
                                                    <input
                                                        className="form-control d-inline-block w-auto ml-2"
                                                        type="number"
                                                        placeholder={trans.get("multipleplaylist.enable_filter_video_like")}
                                                        value={this.state.valueFilterVideoLike}
                                                        onChange={(e) => {
                                                            this.setState({
                                                                valueFilterVideoLike: e.target.value,
                                                                valueFilterVideoLikeError: null
                                                            })
                                                        }}
                                                    />
                                                    <span className="input-group-append">
                                                            <span className="input-group-text"><i
                                                                className="fe fe-thumbs-up"></i></span>
                                                        </span>
                                                    {valueFilterVideoLikeError ? <div className="invalid-feedback"
                                                                                      style={{display: 'block'}}>{valueFilterVideoLikeError}</div> : ""}

                                                </div>
                                            }
                                        </Col>
                                        <Col lg="3">
                                            <label className="custom-control custom-checkbox w-auto d-inline-block">
                                                <input type="checkbox" name="filter_video"
                                                       className="custom-control-input"
                                                       checked={this.state.enableFilterVideoDislike}
                                                       onChange={(e) => {
                                                           this.setState({enableFilterVideoDislike: e.target.checked})
                                                       }}/>
                                                <span className="custom-control-label">
                                                        {trans.get("multipleplaylist.enable_filter_video_disklike")}
                                                    </span>
                                            </label>
                                            {
                                                this.state.enableFilterVideoDislike == true &&
                                                <div className="input-group">
                                                    <input className="form-control d-inline-block w-auto ml-2"
                                                           type="number"
                                                           name="dislikevideo"
                                                           placeholder={trans.get("multipleplaylist.enable_filter_video_disklike")}
                                                           value={this.state.valueFilterVideoDislike}
                                                           onChange={(e) => {
                                                               this.setState({
                                                                   valueFilterVideoDislike: e.target.value,
                                                                   valueFilterVideoDislikeError: null
                                                               })
                                                           }}
                                                    />
                                                    <span className="input-group-append">
                                                            <span className="input-group-text"><i
                                                                className="fe fe-thumbs-down"></i></span>
                                                        </span>
                                                    {valueFilterVideoDislikeError ? <div className="invalid-feedback"
                                                                                         style={{display: 'block'}}>{valueFilterVideoDislikeError}</div> : ""}

                                                </div>
                                            }
                                        </Col>
                                    </>
                                }
                            </div>
                            <Button variant="primary"
                                    onClick={this.submitData}
                            >{trans.get('keyword.add')}</Button>
                        </CardLoaderReact>
                    </Col>
                </Row>
            </Container>
        );
    }
}

if (document.getElementById('section-single-playlist')) {
    ReactDOM.render(<PageSinglePlaylist/>, document.getElementById('section-single-playlist'));
}

