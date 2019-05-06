import React from 'react';
import ReactDOM from 'react-dom';
import {
    Container,
    Row,
    Col,
    Card,
    Button
} from 'react-bootstrap';
import SelectGroupReact from './SelectGroup.react';
import TagsGroupReact from './TagsGroup.react';
import InputGroupReact from './InputGroup.react';
import trans from '../lang';
import {fetch} from '../util/util';
import {URL_PLAYLIST_ITEM_CREATE} from '../util/constant';
const options =  [
    {
        value: 1,
        label: "Option 1",
        path: "https://yt3.ggpht.com/a-/AAuE7mASBnkuVTTqSbPw2jn2igShM_GuM-BeYZulbw=s88-mo-c-c0xffffffff-rj-k-no"

    },
    {
        value: 2,
        label: "Option 2",
        path: "https://yt3.ggpht.com/a-/AAuE7mDqAiIIi6LGZm-QHF1ICAdNyWney320UfIW-A=s88-mo-c-c0xffffffff-rj-k-no"

    }
]
class PageSinglePlaylist extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            idLoading: false,
            message: null,
            messageType: 'info',
            tags: [],
        }
    }
    handleChange = (newTags) =>{
        this.setState({ tags: newTags})
    }

    handlerAdd = () => {
        fetch(URL_PLAYLIST_ITEM_CREATE, 'post', {
            videoId: '0b5WvCAdWO0',
            playlistId: 1
        }).then(result => console.log(result))
            .catch(err => console.log(err));
    }
    render() {
        const {tags} = this.state;
        return(
            <Container>
                <div className="page-header">
                    <div className="page-title">
                        {trans.get('template.menu_sub_add_playlist')}
                    </div>
                </div>
                <Row className="row-cards">
                    <Col lg="12">
                        <Card>
                            {/*<Card.Header>*/}
                            {/*    <Card.Title>{trans.get('template.card_title_channel') }</Card.Title>*/}
                            {/*</Card.Header>*/}
                            <Card.Body>
                                <Button variant='primary' onClick={this.handlerAdd}>Add</Button>
                                <SelectGroupReact
                                    label="Chọn kệnh"
                                    name="channel"
                                    image={true}
                                    options={options}
                                    onChange={e=> console.log(e)}
                                />
                                <TagsGroupReact
                                    tags={tags}
                                    label="Từ khóa cho playlist"
                                    placeholder="Nhập keyword"
                                    onChange={this.handleChange}
                                    error="Field keyword required"
                                />
                                <InputGroupReact
                                    label="Tiêu đề playlist"
                                    type="text"
                                    onChange={e => console.log(e)}
                                />
                            </Card.Body>
                        </Card>
                    </Col>

                </Row>
            </Container>
        );
    }
}

if (document.getElementById('section-single-playlist')) {
    ReactDOM.render(<PageSinglePlaylist/>, document.getElementById('section-single-playlist'));
}

