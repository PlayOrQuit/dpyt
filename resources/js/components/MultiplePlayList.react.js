
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
            tags: [],
            selectedListKeyWord: [],
            itemList: [
                {
                    "channelName": "aA",
                    "idChannel": "aa",
                    "isSelected": true
                },
                {
                    "channelName": "bB",
                    "idChannel": "bb",
                    "isSelected": false
                },
                {
                    "channelName": "cC",
                    "idChannel": "cc",
                    "isSelected": false
                },
                {
                    "channelName": "dD",
                    "idChannel": "dd",
                    "isSelected": false
                },
                {
                    "channelName": "eE",
                    "idChannel": "ee",
                    "isSelected": false
                },
                {
                    "channelName": "fF",
                    "idChannel": "ff",
                    "isSelected": false
                },
                {
                    "channelName": "gG",
                    "idChannel": "gg",
                    "isSelected": false
                }
            ],
            numberPlayList: "",
            titleGroup: "",
            titleGenerateTemplate: "@title_group | @main_keyword | @sub_keyword",
            titlePlayList: [],
            optionsSelectList: [
                { value: 'one', label: 'Option One' },
                { value: 'two', label: 'Option Two' },
                { value: 'three', label: 'Option Three' },
                { value: 'four', label: 'Option Four' },
                { value: 'five', label: 'Option Five' },
                { value: 'six', label: 'Option Six' },
                { value: 'seven', label: 'Option Seven' },
                { value: 'eight', label: 'Option Eight' },
                { value: 'nine', label: 'Option Nine' },
                { value: 'ten', label: 'Option Ten' },
                { value: 'eleven', label: 'Option Eleven' },
            ],
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
        }

        this.handleDelete = this.handleDelete.bind(this);
        this.handleAddition = this.handleAddition.bind(this);
        this.handleDrag = this.handleDrag.bind(this);
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

    hanlderClickChannel = (i) => {
        let list = this.state.itemList;
        list[i].isSelected = !list[i].isSelected;
        this.setState({ listItem: list });
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

        this.setState({ numberPlayList: numberPL });
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

    render() {
        const { tags, selectedListKeyWord, itemList, optionsSelectList } = this.state;
        return (
            <Container>
                <div className="page-header">
                    <div className="page-title">
                        {trans.get('multipleplaylist.menu_sub_add_multiple_playlist')}
                    </div>
                </div>
                <Row className="row-cards">
                    <Col lg="4">
                        <CardLoaderReact>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <Form.Label className="align-items-center d-flex justify-content-center" style={{ fontSize: "18px" }}>
                                            {trans.get('multipleplaylist.select_channel')}
                                            <OverlayTrigger
                                                key='right'
                                                placement='right'
                                                overlay={
                                                    <Tooltip id={'tooltip-right'}>
                                                        {trans.get("multipleplaylist.select_channel_tooltip")}
                                                    </Tooltip>
                                                }
                                            >
                                                <span class="form-help ml-5" aria-describedby="tooltip-right">?</span>
                                            </OverlayTrigger>
                                        </Form.Label>
                                    </h3>
                                </div>
                                <div className="card-body o-auto" style={{ height: '11.5rem' }}>
                                    <ul className="list-unstyled list-separated">
                                        {itemList.map((obj, i) => (
                                            <li className={obj.isSelected ? "list-separated-item cursor-pointer" : " list-separated-item cursor-pointer"}
                                                style={{ padding: "5px 0" }}
                                                onClick={(e) => this.hanlderClickChannel(i)}>
                                                <div className="col">
                                                    <small className="d-inline-block item-except text-sm h-1x">{obj.channelName}</small>
                                                    {(obj.isSelected === true) &&
                                                        <i className="fe fe-check-circle float-right" style={{ color: "#467fcf" }}></i>
                                                    }
                                                </div>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            </div>
                        </CardLoaderReact>
                    </Col>
                    <Col lg='8'>
                        <CardLoaderReact>
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
                                <input name="id_country" placeholder={trans.get("multipleplaylist.country")} type="text" className="form-control" />
                            </div>
                            <div className="form-group d-inline-block ml-1" style={{ width: "49%" }}>
                                <LabelToolTip
                                    title={trans.get("multipleplaylist.language")}
                                    tooltip={trans.get("multipleplaylist.language_tooltips")}
                                    id="language-right"
                                />
                                <input name="id_language" placeholder={trans.get("multipleplaylist.language")} type="text" className="form-control" />
                            </div>
                            <div className="form-group d-flex align-items-center justify-content-center">
                                <button className="btn btn-primary">{trans.get("multipleplaylist.keyword_search")}</button>
                            </div>
                        </CardLoaderReact>
                    </Col>
                    <Col lg="12" id="another_height">
                        <CardLoaderReact>
                            <div className="form-group">
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
                                    <label className='form-label ml-5 d-inline-block w-10'>{trans.get("multipleplaylist.keyword_find", { attribute: this.state.optionsSelectList.length })}</label>
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
                                <label className="form-label float-right"
                                    style={selectedListKeyWord.length != 0 && selectedListKeyWord.length == this.state.numberPlayList ? { color: "green" } : {}}
                                >
                                    {trans.get('multipleplaylist.number_key_word_select', { attribute: selectedListKeyWord.length })} / {this.state.numberPlayList ? this.state.numberPlayList : 0}
                                </label>
                            </div>
                            <div className="form-group">
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
                            <div className="form-group">
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
                                        <Col lg="3">
                                            <label className="custom-control custom-checkbox">
                                                <input type="checkbox" name="filter_video"
                                                    className="custom-control-input"
                                                    checked={this.state.enableFilterVideoTime}
                                                    onChange={(e) => {
                                                        this.setState({ enableFilterVideoTime: e.target.checked })
                                                    }} />
                                                <span className="custom-control-label">
                                                    {trans.get("multipleplaylist.enable_filter_video_time")}
                                                </span>
                                                {
                                                    this.state.enableFilterVideoTime == true &&
                                                    <>
                                                        <select className="form-control"
                                                            value = {this.state.valueFilterEqualTime}
                                                            onChange = {(e) => {
                                                                this.setState({valueFilterEqualTime: e.target.value})
                                                            }}
                                                        >
                                                            <option value="0">{trans.get("multipleplaylist.filter_equal_lower")}</option>
                                                            <option value="1">{trans.get("multipleplaylist.filter_equal_higher")}</option>
                                                        </select>
                                                        <span className="col-auto">
                                                            <input
                                                                className="form-control" type="date"
                                                                value={this.state.valueFilterVideoTime}
                                                                onChange={(e) => {
                                                                    this.setState({ valueFilterVideoTime: e.target.value })
                                                                }}
                                                            />
                                                        </span>
                                                    </>
                                                }
                                            </label>
                                        </Col>
                                        <Col lg="3">
                                            <label className="custom-control custom-checkbox">
                                                <input type="checkbox" name="filter_video"
                                                    className="custom-control-input"
                                                    checked={this.state.enableFilterVideoDuration}
                                                    onChange={(e) => {
                                                        this.setState({ enableFilterVideoDuration: e.target.checked })
                                                    }} />
                                                <span className="custom-control-label">
                                                    {trans.get("multipleplaylist.enable_filter_video_duration")}
                                                </span>
                                                {
                                                    this.state.enableFilterVideoDuration == true &&
                                                    <input
                                                        className="form-control" type="number"
                                                        placeholder={trans.get("multipleplaylist.enable_filter_video_duration")}
                                                        value={this.state.valueFilterVideoDuration}
                                                        onChange={(e) => {
                                                            this.setState({ valueFilterVideoDuration: e.target.value })
                                                        }}
                                                    />
                                                }
                                            </label>
                                        </Col>
                                        <Col lg="3">
                                            <label className="custom-control custom-checkbox">
                                                <input type="checkbox" name="filter_video"
                                                    className="custom-control-input"
                                                    checked={this.state.enableFilterVideoView}
                                                    onChange={(e) => {
                                                        this.setState({ enableFilterVideoView: e.target.checked })
                                                    }} />
                                                <span className="custom-control-label">
                                                    {trans.get("multipleplaylist.enable_filter_video_view")}
                                                </span>
                                                {
                                                    this.state.enableFilterVideoView == true &&
                                                    <input
                                                        className="form-control"
                                                        type="text"
                                                        placeholder={trans.get("multipleplaylist.enable_filter_video_view")}
                                                        value={this.state.valueFilterVideoView}
                                                        onChange={(e) => {
                                                            this.setState({ valueFilterVideoView: e.target.value })
                                                        }}
                                                    />
                                                }
                                            </label>
                                        </Col>
                                        <Col lg="3">
                                            <label className="custom-control custom-checkbox">
                                                <input type="checkbox" name="filter_video"
                                                    className="custom-control-input"
                                                    checked={this.state.enableFilterVideoLike}
                                                    onChange={(e) => {
                                                        this.setState({ enableFilterVideoLike: e.target.checked })
                                                    }} />
                                                <span className="custom-control-label">
                                                    {trans.get("multipleplaylist.enable_filter_video_like")}
                                                </span>
                                                {
                                                    this.state.enableFilterVideoLike == true &&
                                                    <input
                                                        className="form-control"
                                                        type="number"
                                                        placeholder={trans.get("multipleplaylist.enable_filter_video_like")}
                                                        value={this.state.valueFilterVideoLike}
                                                        onChange={(e) => {
                                                            this.setState({ valueFilterVideoLike: e.target.value })
                                                        }}
                                                    />
                                                }
                                            </label>
                                        </Col>
                                        <Col lg="3">
                                            <label className="custom-control custom-checkbox">
                                                <input type="checkbox" name="filter_video"
                                                    className="custom-control-input"
                                                    checked={this.state.enableFilterVideoDislike}
                                                    onChange={(e) => {
                                                        this.setState({ enableFilterVideoDislike: e.target.checked })
                                                    }} />
                                                <span className="custom-control-label">
                                                    {trans.get("multipleplaylist.enable_filter_video_disklike")}
                                                </span>
                                                {
                                                    this.state.enableFilterVideoDislike == true &&
                                                    <input className="form-control"
                                                        type="number"
                                                        name="dislikevideo"
                                                        placeholder={trans.get("multipleplaylist.enable_filter_video_disklike")}
                                                        value={this.state.valueFilterVideoDislike}
                                                        onChange={(e) => {
                                                            this.setState({ valueFilterVideoDislike: e.target.value })
                                                        }}
                                                    />
                                                }
                                            </label>
                                        </Col>
                                    </>
                                }
                            </div>
                            <div className="form-group">
                                <button className="btn btn-primary">
                                    {trans.get("multipleplaylist.create_playlist_btn")}
                                </button>
                            </div>
                        </CardLoaderReact>
                    </Col>
                </Row>
            </Container>
        );
    }
}

if (document.getElementById('multiple-playlist')) {
    ReactDOM.render(<MultiplePlayList />, document.getElementById('multiple-playlist'));
}