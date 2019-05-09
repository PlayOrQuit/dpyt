import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Container from 'react-bootstrap/Container';
import { Col, Row, Form } from 'react-bootstrap';
import CardLoaderReact from './CardLoader.react';
import {
    Alert,
    OverlayTrigger,
    Tooltip
} from 'react-bootstrap';
import {
    STATUS_CODE_OK,
    URL_API_MULTIPLE_PLAY_LIST_LANGUAGES,
    URL_API_MULTIPLE_PLAY_LIST_REGIONS,
    URL_CHANNEL_GET,
    URL_API_MULTIPLE_PLAY_LIST_KEYWORD,
    URL_API_MULTIPLE_PLAY_LIST_CREATE
} from '../util/constant';
import { fetch } from '../util/util';
import LabelToolTip from './LabelToolTip.react';
import { WithContext as ReactTags } from 'react-tag-input';
import trans from '../lang/index';
import DualListBox from 'react-dual-listbox';
import 'react-dual-listbox/lib/react-dual-listbox.css';

const KeyCodes = {
    comma: 188,
    enter: 13,
};

const delimiters = [KeyCodes.comma, KeyCodes.enter];

class MultiplePlayList extends Component {
    constructor(props) {
        super(props);
        this.state = {
            loadingGetListChannel: false,
            loadgingGetLanguages: false,
            loadingCreatePlayList: false,
            listLanguage: [],
            valueLanguage: "vi",
            listRegion: [],
            valueRegion: "VN",
            tags: [],
            optionsSelectList: [],
            selectedListKeyWord: [],
            itemListChannel: [],
            itemListChannelSelected: [],
            numberPlayList: "",
            titleGroup: "",
            titleGenerateTemplate: "@title_group | @main_keyword | @sub_keyword",
            titlePlayList: [],
            descriptionPlayList: "",
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
            valueKeyWord: "",
            message: "",
            messageType:"",
            valueFilterVideoTimeError: null,
            valueFilterVideoDurationError: null,
            valueFilterVideoViewError: null,
            valueFilterVideoLikeError: null,
            valueFilterVideoDislikeError: null
        }

        this.handleDelete = this.handleDelete.bind(this);
        this.handleAddition = this.handleAddition.bind(this);
        this.handleDrag = this.handleDrag.bind(this);
    }

    componentWillMount() {
        this.getDataChannel();
        this.getDataRegion();
        this.getDataLanguage();
    }

    getDataLanguage = () => {
        this.setState({ loadgingGetLanguages: true });
        fetch(URL_API_MULTIPLE_PLAY_LIST_LANGUAGES, 'get', {})
            .then(result => {
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    this.setState({ listLanguage: result.data.body.data, loadgingGetLanguages: false });
                }
            })
            .catch(error => {
                console.log(error)
                this.setState({ loadgingGetLanguages: false });
            });
    }

    getDataRegion = () => {
        this.setState({ loadgingGetLanguages: true });
        fetch(URL_API_MULTIPLE_PLAY_LIST_REGIONS, 'get', {})
            .then(result => {
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    this.setState({ listRegion: result.data.body.data, loadgingGetLanguages: false });
                }
            })
            .catch(error => {
                console.log(error)
                this.setState({ loadgingGetLanguages: false });
            });
    }

    getDataChannel = () => {
        this.setState({ loadingGetListChannel: true });
        fetch(URL_CHANNEL_GET, 'get', {})
            .then(result => {
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    let temp = result.data.body.data;
                    temp.forEach((item) => {
                        item.isSelected = false;
                    })
                    this.setState({
                        itemListChannel: temp,
                        loadingGetListChannel: false
                    });
                }
            })
            .catch(error => {
                console.log(error)
                this.setState({ loadingGetListChannel: false });
            });
    }

    getDataKeyWord = () => {
        if (this.state.tags.length == 0) return;
        let q = "";
        for (const item of this.state.tags) {
            q += item.id + "|";
        }
        q = q.slice(0, -1);
        const region = this.state.valueRegion;
        const language = this.state.valueLanguage;
        fetch(URL_API_MULTIPLE_PLAY_LIST_KEYWORD + "?q=" + q + "&regionCode=" + region + "&relevanceLanguage=" + language,
            'get')
            .then(result => {
                console.log(result)
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    const data = result.data.body.data;
                    let temp = [];
                    data.forEach((item) => {
                        let obj = new Object();
                        obj.value = item
                        obj.label = item
                        temp.push(obj)
                    })
                    console.log(temp)
                    this.setState({
                        optionsSelectList: temp,
                        valueKeyWord: q,
                        selectedListKeyWord: [],
                        titlePlayList: []
                    })
                }
            })
            .catch(error => {
                console.log(error)
                this.setState({ loadingGetListChannel: false });
            });
    }

    handleDelete(i) {
        const { tags } = this.state;
        this.setState({
            tags: tags.filter((tag, index) => index !== i),
        });
    }

    handleAddition(tag) {
        this.setState(state => ({ tags: [...state.tags, tag] }));
    }

    handleDrag(tag, currPos, newPos) {
        const tags = [...this.state.tags];
        const newTags = tags.slice();

        newTags.splice(currPos, 1);
        newTags.splice(newPos, 0, tag);

        this.setState({ tags: newTags });
    }

    hanlderClickChannel = (idx) => {
        this.state.itemListChannel[idx].isSelected = !this.state.itemListChannel[idx].isSelected
        this.state.itemListChannelSelected = this.state.itemListChannel.filter(q => q.isSelected == true);

        if (this.state.itemListChannelSelected.length == 0) {
            this.state.optionsSelectList = [];
        }
        this.setState({
            itemListChannel: this.state.itemListChannel,
            itemListChannelSelected: this.state.itemListChannelSelected,
            optionsSelectList: this.state.optionsSelectList
        });
    }

    hanlderChangeKeyWord = (selectedListKeyWord) => {
        if (selectedListKeyWord.length <= this.state.numberPlayList) {
            this.setState({ selectedListKeyWord: selectedListKeyWord })
        }
    }

    handlerChangeNumberPlayList = (e) => {
        const value = e.target.value
        let numberPL = 0;
        if (value <= 0 && e.target.value != "") {
            numberPL = 0;
        } else if (value >= 10) {
            numberPL = 10;
        } else {
            numberPL = value;
        }

        if (value < this.state.selectedListKeyWord.length) {
            this.state.selectedListKeyWord = [];
            this.state.titlePlayList = [];
        }

        this.setState({
            numberPlayList: numberPL,
            selectedListKeyWord: this.state.selectedListKeyWord,
            titlePlayList: this.state.titlePlayList
        });
    }

    handlerCreatePlayList = () => {
        const {
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
        } = this.state;
        if (enableFilterVideo) {
            if (enableFilterVideoTime) {
                if (valueFilterVideoTime === '') {
                    this.setError('valueFilterVideoTime', trans.get('validation.required', { attribute: trans.get('multipleplaylist.enable_filter_video_time') }));
                    return;
                }
            }
            if (enableFilterVideoDuration) {
                if (valueFilterVideoDuration === '') {
                    this.setError('valueFilterVideoDuration', trans.get('validation.required', { attribute: trans.get('multipleplaylist.enable_filter_video_duration') }));
                    return;
                }
            }
            if (enableFilterVideoView) {
                if (valueFilterVideoView === '') {
                    this.setError('valueFilterVideoView', trans.get('validation.required', { attribute: trans.get('multipleplaylist.enable_filter_video_view') }));
                    return;
                }
            }
            if (enableFilterVideoLike) {
                if (valueFilterVideoLike === '') {
                    this.setError('valueFilterVideoLike', trans.get('validation.required', { attribute: trans.get('multipleplaylist.enable_filter_video_like') }));
                    return;
                }
            }
            if (enableFilterVideoDislike) {
                if (valueFilterVideoDislike === '') {
                    this.setError('valueFilterVideoDislike', trans.get('validation.required', { attribute: trans.get('multipleplaylist.enable_filter_video_disklike') }));
                    return;
                }
            }
        }
        this.setState({ loadingCreatePlayList: true });
        const listChannel = this.state.itemListChannelSelected;
        const listTitleChannel = this.state.titlePlayList;

        if (listChannel.length == 0 || listTitleChannel.length == 0) return;
        var playListItem = { playlist: [] };

        var idx = 0;
        for (const titleText of listTitleChannel) {
            var item = {};
            item.title = titleText;
            item.hl = this.state.valueLanguage;
            item.gl = this.state.valueRegion
            if (idx >= listChannel.length) {
                idx = 0;
            }
            item.channel_id = listChannel[idx].id;
            idx++;
            item.keywords = this.state.valueKeyWord;
            item.description = titleText + " " + this.state.valueKeyWord;

            if (this.state.enableFilterVideo) {
                if (this.state.valueFilterVideoTime == "" ||
                    this.state.valueFilterVideoDislike == "" ||
                    this.state.valueFilterVideoDuration == "" ||
                    this.state.valueFilterVideoLike == "" ||
                    this.state.valueFilterVideoView == "") {

                    item.status_filter = this.state.enableFilterVideo;

                    if (this.state.enableFilterVideoTime) {
                        item.filter_by_date = this.state.valueFilterVideoTime;
                        item.filter_by_date_status = this.state.valueFilterEqualTime;
                    }
                    if (this.state.enableFilterVideoDislike) {
                        item.filter_by_dislike = this.state.valueFilterVideoDislike;
                    }
                    if (this.state.enableFilterVideoDuration) {
                        item.filter_by_duration = this.state.valueFilterVideoDuration;
                    }
                    if (this.state.enableFilterVideoLike) {
                        item.filter_by_like = this.state.valueFilterVideoLike;
                    }
                    if (this.state.enableFilterVideoView) {
                        item.filter_by_view = this.state.valueFilterVideoView;
                    }
                } else {
                    item.status_filter = false;
                }
            }
            playListItem.playlist.push(item);
        }

        fetch(URL_API_MULTIPLE_PLAY_LIST_CREATE, 'post', playListItem)
            .then(result => {
                this.setState({ loadingCreatePlayList: false });
                const data = result.data.body;
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    this.setState({message: "Thêm Playlist thành công", messageType: "success"})
                } 
                else {
                    this.setState({message: "Thêm Playlist thất bại", messageType: "error"})
                }
            })
            .catch(error => {
                console.log(error)
                this.setState({ loadingCreatePlayList: false });
            });
    }

    handlerChangeTitleGroup = (e) => {
        this.setState({ titleGroup: e.target.value })
    }

    onSubmitGenerationTitle = () => {
        const template = this.state.titleGenerateTemplate;
        const listItem = this.state.selectedListKeyWord;
        let listTitle = [];
        for (const item of listItem) {
            const temp = template.replace('@title_group', this.state.titleGroup)
                .replace('@main_keyword', this.state.tags[Math.floor(Math.random() * this.state.tags.length)].id)
                .replace('@sub_keyword', item);
            listTitle.push(temp);
        }

        this.setState({ titlePlayList: listTitle })
    }

    setError(k, msg) {
        if (k === 'channelValue') {
            this.setState({ 'channelError': msg });
        } else if (k === 'keywordValue') {
            this.setState({ 'keywordError': msg });
        } else if (k === 'titlePlaylist') {
            this.setState({ 'titlePlaylistError': msg });
        } else if (k === 'descriptionPlaylist') {
            this.setState({ 'descriptionPlaylistError': msg });
        } else if (k === 'valueFilterVideoTime') {
            this.setState({ 'valueFilterVideoTimeError': msg });
        } else if (k === 'valueFilterVideoDuration') {
            this.setState({ 'valueFilterVideoDurationError': msg });
        } else if (k === 'valueFilterVideoView') {
            this.setState({ 'valueFilterVideoViewError': msg });
        } else if (k === 'valueFilterVideoLike') {
            this.setState({ 'valueFilterVideoLikeError': msg });
        } else if (k === 'valueFilterVideoDislike') {
            this.setState({ 'valueFilterVideoDislikeError': msg });
        }
    }

    render() {
        const now = new Date();
        const { tags,
            selectedListKeyWord,
            itemListChannel,
            optionsSelectList,
            valueFilterVideoTimeError,
            valueFilterVideoDurationError,
            valueFilterVideoViewError,
            valueFilterVideoLikeError,
            valueFilterVideoDislikeError } = this.state;
        return (
            <Container>
                <div className="page-header">
                    <div className="page-title">
                        {trans.get('multipleplaylist.menu_sub_add_multiple_playlist')}
                    </div>
                </div>
                <Row className="row-cards">
                    <Col lg="4">
                        <CardLoaderReact
                            isLoading={this.state.loadingGetListChannel}
                            title={trans.get('multipleplaylist.select_channel')}
                        >
                            <div className="card-body o-auto" style={{ height: '11.5rem', padding: "0" }}>
                                <ul className="list-unstyled list-separated">
                                    {itemListChannel.map((obj, i) =>
                                        (
                                            < li className="list-separated-item cursor-pointer"
                                                onClick={(e) => this.hanlderClickChannel(i)}>
                                                <div className="d-flex align-items-center">
                                                    <div class="col-auto">
                                                        <span class="avatar avatar-md d-block"
                                                            style={{ backgroundImage: "url(" + obj.thumbnail + ")" }}></span>
                                                    </div>
                                                    <div className="col">
                                                        <div>
                                                            {obj.title}
                                                        </div>
                                                        <small className="d-inline-block item-except text-sm h-1x">
                                                            <i className="fe fe-bell"></i> {obj.subscriber}
                                                            <i className="fe fe-play-circle ml-5"></i> {obj.view}
                                                        </small>
                                                        {
                                                            (obj.isSelected) &&
                                                            <i className="fe fe-check-circle float-right"
                                                                style={{ color: "#467fcf" }}></i>
                                                        }
                                                    </div>
                                                </div>
                                            </li>
                                        ))}
                                </ul>
                            </div>
                        </CardLoaderReact>
                    </Col>
                    <Col lg='8' className="another_height">
                        {
                            this.state.itemListChannelSelected.length > 0 &&
                            <CardLoaderReact
                                isLoading={this.state.loadgingGetLanguages}
                                title={trans.get("multipleplaylist.title_input_keyword")}
                            >
                                <div className="form-group">
                                    <LabelToolTip
                                        title={trans.get("multipleplaylist.input_keyword")}
                                        tooltip={trans.get("multipleplaylist.tag_tooltips")}
                                        id="tags-right"
                                    />
                                    <ReactTags
                                        tags={tags}
                                        handleDelete={this.handleDelete}
                                        handleAddition={this.handleAddition}
                                        handleDrag={this.handleDrag}
                                        delimiters={delimiters}
                                        autofocus={false}
                                        inline={true}
                                        placeholder={trans.get("multipleplaylist.input_keyword")} />
                                </div>
                                <div className="form-group w-50 d-inline-block">
                                    <LabelToolTip
                                        title={trans.get("multipleplaylist.country")}
                                        tooltip={trans.get("multipleplaylist.country_tooltips")}
                                        id="country-right"
                                    />
                                    <select className="form-control"
                                        value={this.state.valueRegion}
                                        onChange={(e) => {
                                            this.setState({ valueRegion: e.target.value })
                                        }}
                                    >
                                        {
                                            this.state.listRegion &&
                                            this.state.listRegion.map((obj) => (
                                                <option value={obj.gl}>{obj.name}</option>
                                            ))
                                        }
                                    </select>
                                </div>
                                <div className="form-group d-inline-block ml-1" style={{ width: "49%" }}>
                                    <LabelToolTip
                                        title={trans.get("multipleplaylist.language")}
                                        tooltip={trans.get("multipleplaylist.language_tooltips")}
                                        id="language-right"
                                    />
                                    <select className="form-control"
                                        value={this.state.valueLanguage}
                                        onChange={(e) => {
                                            this.setState({ valueLanguage: e.target.value })
                                        }}
                                    >
                                        {
                                            this.state.listLanguage &&
                                            this.state.listLanguage.map((obj) => (
                                                <option value={obj.hl}>{obj.name}</option>
                                            ))
                                        }
                                    </select>
                                </div>
                                <div className="form-group d-flex align-items-center justify-content-center">
                                    <button
                                        onClick={this.getDataKeyWord}
                                        className="btn btn-primary">{trans.get("multipleplaylist.keyword_search")}</button>
                                </div>
                            </CardLoaderReact>
                        }
                    </Col>
                    <Col lg="12" className="another_height">
                        {
                            this.state.optionsSelectList.length > 0 &&
                            <CardLoaderReact isLoading={this.state.loadingCreatePlayList}>
                                <div className="form-group w-50 d-block">
                                    <LabelToolTip
                                        title={trans.get("multipleplaylist.number_create_playlist")}
                                        tooltip={trans.get("multipleplaylist.number_create_playlist_tooltip")}
                                        id="language-right"
                                    />
                                    <input name="id_count_playlist"
                                        placeholder={trans.get("multipleplaylist.number_create_playlist")}
                                        type="number" className="form-control"
                                        value={this.state.numberPlayList}
                                        onChange={(e) => this.handlerChangeNumberPlayList(e)}
                                    />
                                </div>
                                <div className="form-group">
                                    <LabelToolTip
                                        title={trans.get("multipleplaylist.select_keyword")}
                                        tooltip={trans.get("multipleplaylist.select_keyword_tooltip")}
                                        id="keyword-selected-right"
                                        className="d-inline-block w-25"
                                    />
                                    {optionsSelectList &&
                                        <label
                                            className='form-label ml-5 d-inline-block w-10'>{trans.get("multipleplaylist.keyword_find", { attribute: this.state.optionsSelectList.length })}</label>
                                    }
                                    <DualListBox
                                        options={optionsSelectList}
                                        selected={selectedListKeyWord}
                                        onChange={(selectedListKeyWord) => {
                                            this.hanlderChangeKeyWord(selectedListKeyWord);
                                        }}
                                        icons={{
                                            moveLeft: <span className="fe fe-chevron-left" />,
                                            moveAllLeft: [
                                                <span key={0} className="fe fe-chevron-left" />,
                                                <span key={1} className="fe fe-chevron-left" />,
                                            ],
                                            moveRight: <span className="fe fe-chevron-right" />,
                                            moveAllRight: [
                                                <span key={0} className="fe fe-chevron-right" />,
                                                <span key={1} className="fe fe-chevron-right" />,
                                            ],
                                            moveDown: <span className="fe fe-chevron-down" />,
                                            moveUp: <span className="fe fe-chevron-up" />,
                                        }}
                                    />
                                    {
                                        this.state.selectedListKeyWord.length > 0 &&
                                        <label className="form-label float-right"
                                            style={selectedListKeyWord.length != 0 && selectedListKeyWord.length == this.state.numberPlayList ? { color: "green" } : {}}
                                        >
                                            {trans.get('multipleplaylist.number_key_word_select', { attribute: selectedListKeyWord.length })} / {this.state.numberPlayList ? this.state.numberPlayList : 0}
                                        </label>
                                    }
                                </div>
                                <div className="form-group w-50 d-block">
                                    <LabelToolTip
                                        title={trans.get("multipleplaylist.title_group_generation")}
                                        tooltip={trans.get("multipleplaylist.title_group_generation")}
                                        id="title-group-right"
                                    />
                                    <input name="id_group_title"
                                        placeholder={trans.get("multipleplaylist.title_group_generation")}
                                        type="text" className="form-control"
                                        value={this.state.titleGroup}
                                        onChange={(e) => this.handlerChangeTitleGroup(e)}
                                    />
                                </div>
                                <div className="form-group w-50 d-block">
                                    <LabelToolTip
                                        title={trans.get("multipleplaylist.title_auto_genrenation_template")}
                                        tooltip={trans.get("multipleplaylist.title_auto_genrenation_template_tooltip")}
                                        id="title-template-right"
                                    />
                                    <div className="input-group">
                                        <input name="id_group_title"
                                            placeholder={trans.get("multipleplaylist.number_create_playlist")}
                                            type="text" className="form-control"
                                            value={this.state.titleGenerateTemplate}
                                            onChange={(e) => {
                                                this.setState({ titleGenerateTemplate: e.target.value })
                                            }}
                                        />
                                        <span className="input-group-append">
                                            <button
                                                className="btn btn-primary"
                                                onClick={this.onSubmitGenerationTitle}>
                                                {trans.get("multipleplaylist.using_title_auto_generation")}
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                {selectedListKeyWord &&
                                    selectedListKeyWord.length > 0 &&
                                    selectedListKeyWord.map((obj, i) => (
                                        <div className="form-group">
                                            <LabelToolTip
                                                title={trans.get("multipleplaylist.title_index_at", { attribute: i + 1 })}
                                                tooltip={trans.get("multipleplaylist.title_index_at", { attribute: i + 1 })}
                                                id={"title-template-right-" + i}
                                            />
                                            <input readOnly
                                                name={"id_playlist_title_" + i}
                                                type="text" className="form-control"
                                                value={this.state.titlePlayList[i]}
                                            />
                                        </div>
                                    ))
                                }
                                <div className="form-group">
                                    <LabelToolTip
                                        title={trans.get("multipleplaylist.description_playlist")}
                                        tooltip={trans.get("multipleplaylist.description_playlist_tooltip")}
                                        id='description-playlist'
                                    />
                                    <textarea
                                        rows="5"
                                        className="w-100"
                                        name="description"
                                        value={this.state.descriptionPlayList}
                                        onChange={(e) => {
                                            this.setState({ descriptionPlayList: e.target.value })
                                        }}>

                                    </textarea>
                                </div>
                                <div className="form-group">
                                    <label className="custom-control custom-checkbox w-auto d-inline-block">
                                        <input type="checkbox" name="filter_video"
                                            className="custom-control-input"
                                            checked={this.state.enableFilterVideo}
                                            onChange={(e) => {
                                                this.setState({ enableFilterVideo: e.target.checked })
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
                                                            this.setState({ enableFilterVideoTime: e.target.checked })
                                                        }} />
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
                                                                    style={{ padding: "5px 0" }}>
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
                                                                placeholder={now.getFullYear() + '-' + (now.getMonth() + 1) + '-' + now.getDate()} autoComplete="off"
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
                                                                style={{ display: 'block' }}>{valueFilterVideoTimeError}</div> : ""}
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
                                                            this.setState({ enableFilterVideoDuration: e.target.checked })
                                                        }} />
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
                                                            style={{ display: 'block' }}>{valueFilterVideoDurationError}</div> : ""}
                                                    </div>
                                                }
                                            </Col>
                                            <Col lg="3">
                                                <label className="custom-control custom-checkbox w-auto d-inline-block">
                                                    <input type="checkbox" name="filter_video"
                                                        className="custom-control-input"
                                                        checked={this.state.enableFilterVideoView}
                                                        onChange={(e) => {
                                                            this.setState({ enableFilterVideoView: e.target.checked })
                                                        }} />
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
                                                            style={{ display: 'block' }}>{valueFilterVideoViewError}</div> : ""}

                                                    </div>
                                                }
                                            </Col>
                                            <Col lg="3">
                                                <label className="custom-control custom-checkbox w-auto d-inline-block">
                                                    <input type="checkbox" name="filter_video"
                                                        className="custom-control-input"
                                                        checked={this.state.enableFilterVideoLike}
                                                        onChange={(e) => {
                                                            this.setState({ enableFilterVideoLike: e.target.checked })
                                                        }} />
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
                                                            style={{ display: 'block' }}>{valueFilterVideoLikeError}</div> : ""}

                                                    </div>
                                                }
                                            </Col>
                                            <Col lg="3">
                                                <label className="custom-control custom-checkbox w-auto d-inline-block">
                                                    <input type="checkbox" name="filter_video"
                                                        className="custom-control-input"
                                                        checked={this.state.enableFilterVideoDislike}
                                                        onChange={(e) => {
                                                            this.setState({ enableFilterVideoDislike: e.target.checked })
                                                        }} />
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
                                                            style={{ display: 'block' }}>{valueFilterVideoDislikeError}</div> : ""}

                                                    </div>
                                                }
                                            </Col>
                                        </>
                                    }
                                </div>
                                <div className="form-group">
                                    <button
                                        onClick={this.handlerCreatePlayList}
                                        className="btn btn-primary">
                                        {trans.get("multipleplaylist.create_playlist_btn")}
                                    </button>
                                    {this.state.message ?
                                        <Alert style={{marginTop: "10px"}} onClose={(e) => { this.setState({ message: "" }) }} dismissible variant={this.state.messageType}>
                                            <p>{this.state.message}</p></Alert> : ''}
                                </div>
                            </CardLoaderReact>
                        }
                    </Col>
                </Row>
            </Container>
        );
    }
}

if (document.getElementById('multiple-playlist')) {
    ReactDOM.render(<MultiplePlayList />, document.getElementById('multiple-playlist'));
}
