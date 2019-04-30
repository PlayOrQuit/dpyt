import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import {
    Card,
    Form,
    Button,
    Table,
    Pagination,
} from 'react-bootstrap';
import trans from '../lang/index';
import {fetch, fetchOther} from '../util/util';
import {
    URL_CHANNEL_CALLBACK,
    URL_API_KEY_GET_PRIMARY,
    URL_YOUTUBE_CHANNEL_LIST,
    STATUS_CODE_OK
} from '../util/constant';

class PageChannelReact extends Component {
    constructor(props) {
        super(props);
        this.state = {
            urlCallback: null,
            api_key: null,
            auth: null,
            channels: [],
            isLoading: false,
        }
    }
    updateState = (k, v) => {
        const newState = this.state;
        newState[k] = v;
        this.setState(newState);
    }
    componentWillMount() {
        fetch(URL_API_KEY_GET_PRIMARY, 'get', {})
            .then(result => {
                if (result.data.body.statusCode === STATUS_CODE_OK) {
                    if (result.data.body.data[0]) {
                        const api_key = result.data.body.data[0];
                        this.updateState('api_key', api_key);
                        this.updateState('urlCallback', URL_CHANNEL_CALLBACK + '?client_id=' + api_key.id_client);
                    }
                }
            })
            .catch(error => {
                console.log(error);
            });
        window.addEventListener("message", this.receiveMessage, false);
    }
    receiveMessage = event => {
        try {
            const auth = JSON.parse(event.data);
            if(auth.access_token && auth.refresh_token){
                this.updateState('auth', auth);
                fetchOther(URL_YOUTUBE_CHANNEL_LIST + '?part=snippet,statistics&mine=true', 'get', {}, {
                    'Authorization': 'Bearer ' + auth.access_token,
                    'Content-Type': 'application/json',
                }).then(result => {
                    console.log(result);
                }).catch(error => {
                    console.log(error);
                });
            }
        }catch (e) {

        }
    }
    handlerAddChannel = () => {
        if (this.state.urlCallback) {
            window.open(this.state.urlCallback, '_blank');
        } else {
            alert(trans.get('message.set_primary_api_key'));
        }
    }
    render() {
        return (
            <Container>
                <div className="page-header">
                    <div className="page-title">
                        {trans.get('template.menu_sub_channels')}
                    </div>
                    <div className="page-options d-flex">
                        <Button
                            variant="secondary"
                            onClick={this.handlerAddChannel}>
                            {trans.get('keyword.add')}
                        </Button>
                    </div>
                </div>
                <Row className="row-cards">
                    {/*<Col lg="4">*/}
                    {/*    <Card>*/}
                    {/*        <Card.Body>*/}
                    {/*            <Form.Group>*/}
                    {/*                <Form.Label>{trans.get('keyword.name_channel')}</Form.Label>*/}
                    {/*                <Form.Control type="text"*/}
                    {/*                              placeholder={trans.get('keyword.enter') + ' ' + trans.get('keyword.name_channel')}/>*/}
                    {/*            </Form.Group>*/}
                    {/*            <Form.Group>*/}
                    {/*                <Form.Label>{trans.get('keyword.status')}</Form.Label>*/}
                    {/*                <Form.Control as="select">*/}
                    {/*                    <option value="1">1</option>*/}
                    {/*                    <option value="2">2</option>*/}
                    {/*                    <option value="3">3</option>*/}
                    {/*                </Form.Control>*/}
                    {/*            </Form.Group>*/}
                    {/*        </Card.Body>*/}
                    {/*        <Card.Footer>*/}
                    {/*            <Button variant="primary">{trans.get('keyword.search')}</Button>*/}
                    {/*        </Card.Footer>*/}
                    {/*    </Card>*/}
                    {/*</Col>*/}
                    <Col lg="12">
                        <Card>

                            <Table className="card-table table-vcenter">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>{trans.get('keyword.name_channel')}</th>
                                    <th>{trans.get('keyword.view')}</th>
                                    <th>{trans.get('keyword.follow')}</th>
                                    <th>{trans.get('keyword.status')}</th>
                                    <th className="w-1"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Kênh 1</td>
                                    <td>20</td>
                                    <td>50</td>
                                    <td>Bình thường</td>
                                    <td className="text-center">
                                        <div className="item-action dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown" className="icon">
                                                <i className="fe fe-more-vertical"></i>
                                            </a>
                                            <div className="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-edit-2"></i> {trans.get('keyword.edit')}
                                                </a>
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-delete"></i> {trans.get('keyword.delete')}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Kênh 1</td>
                                    <td>20</td>
                                    <td>50</td>
                                    <td>Bình thường</td>
                                    <td className="text-center">
                                        <div className="item-action dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown" className="icon">
                                                <i className="fe fe-more-vertical"></i>
                                            </a>
                                            <div className="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-edit-2"></i> {trans.get('keyword.edit')}
                                                </a>
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-delete"></i> {trans.get('keyword.delete')}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Kênh 1</td>
                                    <td>20</td>
                                    <td>50</td>
                                    <td>Bình thường</td>
                                    <td className="text-center">
                                        <div className="item-action dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown" className="icon">
                                                <i className="fe fe-more-vertical"></i>
                                            </a>
                                            <div className="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-edit-2"></i> {trans.get('keyword.edit')}
                                                </a>
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-delete"></i> {trans.get('keyword.delete')}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Kênh 1</td>
                                    <td>20</td>
                                    <td>50</td>
                                    <td>Bình thường</td>
                                    <td className="text-center">
                                        <div className="item-action dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown" className="icon">
                                                <i className="fe fe-more-vertical"></i>
                                            </a>
                                            <div className="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-edit-2"></i> {trans.get('keyword.edit')}
                                                </a>
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-delete"></i> {trans.get('keyword.delete')}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Kênh 1</td>
                                    <td>20</td>
                                    <td>50</td>
                                    <td>Bình thường</td>
                                    <td className="text-center">
                                        <div className="item-action dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown" className="icon">
                                                <i className="fe fe-more-vertical"></i>
                                            </a>
                                            <div className="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-edit-2"></i> {trans.get('keyword.edit')}
                                                </a>
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-delete"></i> {trans.get('keyword.delete')}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Kênh 1</td>
                                    <td>20</td>
                                    <td>50</td>
                                    <td>Bình thường</td>
                                    <td className="text-center">
                                        <div className="item-action dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown" className="icon">
                                                <i className="fe fe-more-vertical"></i>
                                            </a>
                                            <div className="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-edit-2"></i> {trans.get('keyword.edit')}
                                                </a>
                                                <a href="javascript:void(0)" className="dropdown-item"><i
                                                    className="dropdown-icon fe fe-delete"></i> {trans.get('keyword.delete')}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </Table>
                            <br/>
                            <Row className="justify-content-md-center">
                                <Pagination>
                                    <Pagination.First/>
                                    <Pagination.Prev/>
                                    <Pagination.Item>{1}</Pagination.Item>
                                    <Pagination.Ellipsis/>

                                    <Pagination.Item>{10}</Pagination.Item>
                                    <Pagination.Item>{11}</Pagination.Item>
                                    <Pagination.Item active>{12}</Pagination.Item>
                                    <Pagination.Item>{13}</Pagination.Item>
                                    <Pagination.Item disabled>{14}</Pagination.Item>

                                    <Pagination.Ellipsis/>
                                    <Pagination.Item>{20}</Pagination.Item>
                                    <Pagination.Next/>
                                    <Pagination.Last/>
                                </Pagination>
                            </Row>
                        </Card>
                    </Col>
                </Row>
            </Container>
        );
    }
}

if (document.getElementById('section-channel')) {
    ReactDOM.render(<PageChannelReact/>, document.getElementById('section-channel'));
}